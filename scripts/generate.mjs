import { readFile, writeFile, mkdir } from 'node:fs/promises';
const root = new URL('../', import.meta.url);
const manifest = JSON.parse(await readFile(new URL('../tests/contracts.json', import.meta.url), 'utf8'));
await mkdir(new URL('resources/', root), {recursive:true});
await writeFile(new URL('resources/contracts.json', root), JSON.stringify(manifest,null,2)+'\n');
for (const family of ['activation','rental','email']) {
  const name = family[0].toUpperCase()+family.slice(1);
  let source = `<?php\ndeclare(strict_types=1);\nnamespace SMSPVA;\n// Generated from sdk/contracts.json. Run scripts/generate.mjs in this workspace.\nfinal class ${name}\n{\n    public function __construct(private Transport $transport) {}\n`;
  for (const [operation, op] of Object.entries(manifest.operations).filter(([, op])=>op.family===family)) {
    const field = p => ({integer:'int',number:'float',string:'string',boolean:'bool'})[p.schema.type]??'mixed';
    const shape = op.params.length ? `array{${op.params.map(p=>`${p.name}${p.required?'':'?'}:${field(p)}${p.required?'':'|null'}`).join(',')}}` : 'array{}';
    source += `\n    /**\n     * ${op.method} ${op.path}${op.action ? '?method='+op.action : ''}\n     * @param ${shape} $params\n     * @param array{timeout?:float|int,useApiKey?:bool} $options\n     * @return array<string,mixed> Original API response; see resources/contracts.json.\n     */\n    public function ${op.jsName}(array $params = [], array $options = []): array\n    {\n        return $this->transport->request('${operation}', $params, $options);\n    }\n`;
  }
  if (family==='activation') source += `\n    /** Saved Unix expiry in seconds. Never creates or cancels an order. */\n    public function waitForSms(int $orderid, float $expiresAt, float $interval = 5.0, ?string $partnerkey = null): array\n    {\n        return $this->transport->waitForSms($orderid, $expiresAt, $interval, $partnerkey);\n    }\n`;
  await writeFile(new URL(`src/${name}.php`,root),source+'}\n');
}
console.log('Generated 58 PHP methods and bundled contracts.');
let index='# PHP method index\n\nEach method takes a parameter array and an optional options array. PHPDoc in `src/` contains input shapes; `resources/contracts.json` preserves wire schemas.\n\n';
for(const family of ['activation','rental','email']) {
  index+=`## ${family}\n\n| Method | HTTP | Parameters |\n| --- | --- | --- |\n`;
  for(const op of Object.values(manifest.operations).filter(op=>op.family===family))index+=`| \`$client->${family}->${op.jsName}\` | \`${op.method} ${op.path}${op.action?'?method='+op.action:''}\` | ${op.params.length?op.params.map(p=>'`'+p.name+'`'+(p.required?' (required)':'')).join(', '):'None'} |\n`;
  index+='\n';
}
index+='Activation also provides `waitForSms($orderid, $expiresAt, $interval = 5.0, $partnerkey = null)`, where expiry is Unix seconds.\n';
await writeFile(new URL('METHODS.md',root),index);
