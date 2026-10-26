import assert from 'node:assert/strict';
import {readFile} from 'node:fs/promises';
import {createServer} from 'node:http';
import {spawn} from 'node:child_process';
import {fileURLToPath} from 'node:url';
import {dirname,join} from 'node:path';
const cases=JSON.parse(await readFile(new URL('./cases.json',import.meta.url),'utf8'));
const {operations}=JSON.parse(await readFile(new URL('./contracts.json',import.meta.url),'utf8'));
let count=0, failure;
const server=createServer((req,res)=>{
  const index=count++;
  try {
    assert.equal(req.headers.cookie,undefined);
    if(index<56) {
      const item=cases[index],op=operations[item.key],url=new URL(req.url,'http://localhost');
      assert.equal(req.method,op.method);
      let path=op.path;for(const p of op.params.filter(p=>p.in==='path'))path=path.replace('{'+p.name+'}',encodeURIComponent(String(item.params[p.name])));
      assert.equal(url.pathname,path);
      const query={};if(op.action)query.method=op.action;
      for(const p of op.params.filter(p=>p.in==='query'))if(item.params[p.name]!==undefined&&!(p.omitEmpty&&item.params[p.name]===''))query[p.name]=String(item.params[p.name]);
      if(op.family!=='activation'&&op.auth!=='none')query.apikey='php-local-key-+&?=/';
      assert.deepEqual(Object.fromEntries(url.searchParams),query);
      assert.equal(req.headers.apikey,op.family==='activation'&&op.auth!=='none'?'php-local-key-+&?=/':undefined);
      res.setHeader('Content-Type','application/json');res.end(JSON.stringify(item.response));return;
    }
    if(index===56)res.setHeader('Set-Cookie','secret=must-not-return');
    if(index===58){res.statusCode=302;res.setHeader('Location','/should-not-follow');}
    const finish=()=>res.end(JSON.stringify({status:1,data:[]}));
    if(index===59)setTimeout(finish,200);else finish();
  }catch(error){failure=error;res.statusCode=500;res.end('{}');}
});
await new Promise(resolve=>server.listen(0,'127.0.0.1',resolve));
try {
  const php=process.env.SMSPVA_PHP_BINARY??(process.platform==='win32'?'C:/Program Files/PHP/php.exe':'php');
  const phpArgs=process.platform==='win32'?['-n','-d',`extension_dir=${join(dirname(php),'ext')}`,'-d','extension=curl']:[];
  const child=spawn(php,[...phpArgs,fileURLToPath(new URL('./live.php',import.meta.url))],{env:{...process.env,SMSPVA_TEST_BASE:`http://127.0.0.1:${server.address().port}`},stdio:'inherit',windowsHide:true});
  await new Promise((resolve,reject)=>{child.on('error',reject);child.on('exit',code=>code===0?resolve():reject(new Error(`PHP exit ${code}`)));});
  if(failure)throw failure;assert.equal(count,60);
}finally{await new Promise(resolve=>server.close(resolve));}
