<?php
declare(strict_types=1);
require __DIR__ . '/../autoload.php';
use SMSPVA\Client;
use SMSPVA\ApiError;
use SMSPVA\ProtocolError;
use SMSPVA\ValidationError;
use SMSPVA\TransportError;
use SMSPVA\RequestTimeoutError;
use SMSPVA\TransportFailure;
use SMSPVA\PollingTimeoutError;

function check(bool $condition, string $message): void { if (!$condition) throw new RuntimeException($message); }
function expect(string $class, callable $call): Throwable {
    try { $call(); } catch (Throwable $error) { check($error instanceof $class, 'Wrong error: ' . get_class($error)); return $error; }
    throw new RuntimeException('Missing expected error: ' . $class);
}
$cases = json_decode(file_get_contents(__DIR__ . '/./cases.json'), true, 512, JSON_THROW_ON_ERROR);
$manifest = json_decode(file_get_contents(__DIR__ . '/./contracts.json'), true, 512, JSON_THROW_ON_ERROR);
check($manifest === json_decode(file_get_contents(__DIR__ . '/../resources/contracts.json'), true), 'Stale PHP contracts');
$secret = 'php-local-key-+&?=/'; $calls = []; $body = []; $status = 200;
$http = static function ($url, $method, $headers, $timeout) use (&$calls, &$body, &$status): array {
    $calls[] = compact('url','method','headers','timeout');
    return ['status' => $status, 'body' => json_encode($body, JSON_THROW_ON_ERROR)];
};
$client = new Client($secret, ['transport' => $http]);
foreach ($cases as $case) {
    $body = $case['response'];
    $response = $client->{$case['family']}->{$case['jsName']}($case['params']);
    check($response === $body, 'Raw response changed: ' . $case['key']);
    $op = $manifest['operations'][$case['key']]; $request = end($calls); $url = parse_url($request['url']);
    check($request['method'] === $op['method'], 'Wrong HTTP method');
    $path = $op['path']; $expectedQuery = isset($op['action']) ? ['method' => $op['action']] : [];
    foreach ($op['params'] as $p) {
        $value = $case['params'][$p['name']] ?? null;
        if ($value === null || (($p['omitEmpty'] ?? false) && $value === '')) continue;
        if ($p['in'] === 'path') $path = str_replace('{' . $p['name'] . '}', rawurlencode((string)$value), $path);
        if ($p['in'] === 'query') $expectedQuery[$p['name']] = (string)$value;
    }
    if ($op['family'] !== 'activation' && $op['auth'] !== 'none') $expectedQuery['apikey'] = $secret;
    check($url['path'] === $path, 'Wrong URL path'); $actualQuery = []; parse_str($url['query'] ?? '', $actualQuery);
    check($actualQuery == $expectedQuery, 'Wrong query/auth: ' . $case['key']);
    check(($request['headers']['apikey'] ?? null) === ($op['family'] === 'activation' && $op['auth'] !== 'none' ? $secret : null), 'Wrong auth header');
}
check(count($calls) === 61, 'Expected 61 requests');
echo "PASS: 61 methods, verbs, paths, query names, auth and original results\n";

$body = ['status' => 1, 'data' => []];
$client->email->getData(['pcode' => '']); parse_str(parse_url(end($calls)['url'], PHP_URL_QUERY), $query); check(!isset($query['pcode']) && !isset($query['apikey']), 'Empty public provider/auth sent');
$client->email->getData(['pcode' => 'mail+&?=/中文']); parse_str(parse_url(end($calls)['url'], PHP_URL_QUERY), $query); check($query['pcode'] === 'mail+&?=/中文', 'Provider encoding lost');
$body = ['statusCode' => 200, 'data' => []];
$client->activation->getOperators(['country' => 'a/+?&=中文']); check(str_ends_with(parse_url(end($calls)['url'], PHP_URL_PATH), rawurlencode('a/+?&=中文')), 'Path encoding lost');
$optional = current(array_filter($cases, static fn($case) => $case['auth'] === 'optional'));
$body = $optional['response']; $client->{$optional['family']}->{$optional['jsName']}($optional['params'], ['useApiKey'=>false]);
parse_str(parse_url(end($calls)['url'], PHP_URL_QUERY), $query); check(!isset($query['apikey']), 'Optional key opt-out failed');
echo "PASS: optional/public auth, provider omission and URL encoding\n";

$count = count($calls);
expect(ValidationError::class, static fn() => $client->activation->getOperators([]));
expect(ValidationError::class, static fn() => $client->activation->receiveSms(['orderid'=>'123']));
expect(ValidationError::class, static fn() => $client->activation->getOperators(['country'=>'..']));
expect(ValidationError::class, static fn() => $client->email->getProviders(['apikey'=>'bad']));
expect(ValidationError::class, static fn() => $client->email->createOrder(['scode'=>'x','dtype'=>'invalid','dcount'=>1]));
expect(ValidationError::class, static fn() => $client->rental->getMultiServiceCount([]));
expect(ValidationError::class, static fn() => (new Client(null, ['transport'=>$http]))->activation->getBalance());
expect(ValidationError::class, static fn() => new Client("line\nbreak"));
expect(ValidationError::class, static fn() => new Client(null, ['timeout'=>0]));
check(count($calls) === $count, 'Invalid params reached upstream');
echo "PASS: argument types, required values, enums and credentials validated before HTTP\n";

$status = 202; $body = ['statusCode'=>202,'error'=>['type'=>'SMS_NOT_YET_RECEIVED']];
$pending = $client->activation->receiveSms(['orderid'=>123]); check(Client::isPending($pending), 'Pending lost');
$body = ['statusCode'=>202,'error'=>['type'=>'NUMBER_NOT_READY','description'=>'Wait'],'retryAfter'=>17];
check($client->activation->repeatNumber(['number'=>'01234','service'=>'opt164']) === $body, 'Number pending response changed');
$body = ['statusCode'=>202,'error'=>['type'=>'PHONE_NOT_READY','description'=>'Wait']];
check($client->activation->getPhoneStatus(['orderid'=>123]) === $body, 'Phone pending response changed');
$status = 200; $body = ['status'=>2];
$noStock = $client->email->createOrder(['scode'=>'example','dtype'=>'week','dcount'=>1]); check(Client::isNoStock($noStock), 'No stock lost');
expect(ApiError::class, static fn() => $client->email->getProviders());
$status = 402; $body = ['statusCode'=>402,'error'=>['type'=>'NO_BALANCE']];
$error = expect(ApiError::class, static fn() => $client->activation->getBalance());
check($error->httpStatus === 402 && $error->apiCode === 'NO_BALANCE' && $error->response === $body, 'API error metadata changed');
$status = 200; $body = ['status'=>0,'errorid'=>7,'errormsg'=>'original'];
$error = expect(ApiError::class, static fn() => $client->email->getProviders()); check($error->apiCode === 7, 'Error ID lost');
$body = ['statusCode'=>200,'error'=>[]];
expect(ApiError::class, static fn() => $client->activation->getBalance());
foreach ([[], ['status'=>'1'], ['status'=>true], ['status'=>1.5]] as $invalid) {
    $body = $invalid; expect(ProtocolError::class, static fn() => $client->email->getProviders());
}
$html = new Client($secret, ['transport'=>static fn()=>['status'=>503,'body'=>'<html>unavailable</html>']]);
expect(ProtocolError::class, static fn() => $html->email->getProviders());
echo "PASS: pending/no-stock, HTTP and API errors, invalid envelopes and HTML\n";

$attempts = 0;
$failed = new Client($secret, ['transport'=>static function () use (&$attempts) { $attempts++; throw new TransportFailure('timeout'); }]);
$params = current(array_filter($cases, static fn($case) => $case['key'] === 'activation.getNumber'))['params'];
$error = expect(RequestTimeoutError::class, static fn() => $failed->activation->getNumber($params));
check($attempts === 1 && $error->outcomeUnknown && strpos($error->getMessage(), $secret) === false, 'Unsafe retry or leaked key');
$network = new Client($secret, ['transport'=>static fn()=>throw new RuntimeException('bad URL with ' . $secret)]);
$error = expect(TransportError::class, static fn() => $network->activation->getBalance()); check(!$error->outcomeUnknown && strpos($error->getMessage(), $secret) === false, 'Read transport metadata/leak');
echo "PASS: single attempt on timeout/network failure, explicit unknown outcomes, safe exception messages\n";

$count = count($calls);
expect(PollingTimeoutError::class, static fn() => $client->activation->waitForSms(123, microtime(true)-1));
check(count($calls) === $count, 'Expired helper made request');
expect(ValidationError::class, static fn() => $client->activation->waitForSms(123, microtime(true)+10, 1));
$status = 202; $body = ['statusCode'=>202,'error'=>['type'=>'SMS_NOT_YET_RECEIVED']];
expect(PollingTimeoutError::class, static fn() => $client->activation->waitForSms(123, microtime(true)+0.02));
check(count($calls) === $count + 1, 'Helper reset expiry or made extra request');
$status = 200; $body = ['statusCode'=>200,'data'=>['sms'=>'12345']];
check($client->activation->waitForSms(123, microtime(true)+1) === $body, 'Successful helper result changed');
echo "PASS: saved polling deadlines, expiry, minimum interval and success\n";
echo "PHP SDK: 7 test groups passed\n";
