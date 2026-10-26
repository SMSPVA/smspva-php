<?php
declare(strict_types=1);
$root = $argv[1] ?? null;
if (!is_string($root) || !is_file($root . '/autoload.php')) throw new RuntimeException('Pass an unpacked package directory');
require $root . '/autoload.php';
$contracts = json_decode(file_get_contents($root . '/resources/contracts.json'), true, 512, JSON_THROW_ON_ERROR);
if (count($contracts['operations']) !== 56) throw new RuntimeException('Packaged contract missing');
$client = new SMSPVA\Client(null, ['transport'=>static fn()=>['status'=>200,'body'=>'{"status":1,"data":[]}']]);
if ($client->email->getProviders() !== ['status'=>1,'data'=>[]]) throw new RuntimeException('Packaged SDK failed');
echo "PHP archive: autoload, contracts and public SDK method passed\n";
