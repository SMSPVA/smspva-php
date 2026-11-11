<?php
declare(strict_types=1);
require __DIR__ . '/../autoload.php';
use SMSPVA\Client;
use SMSPVA\ApiError;
use SMSPVA\RequestTimeoutError;
$base = getenv('SMSPVA_TEST_BASE');
if (!preg_match('~^http://127\.0\.0\.1:\d+$~', $base)) throw new RuntimeException('Loopback only');
$client = new Client('php-local-key-+&?=/', ['activationBaseUrl'=>$base, 'rentalBaseUrl'=>$base, 'emailBaseUrl'=>$base]);
$cases = json_decode(file_get_contents(__DIR__.'/./cases.json'), true, 512, JSON_THROW_ON_ERROR);
foreach ($cases as $case) {
    $result = $client->{$case['family']}->{$case['jsName']}($case['params']);
    if ($result !== $case['response']) throw new RuntimeException('Wrong loopback response');
}
$client->email->getProviders(); $client->email->getProviders();
try { $client->email->getProviders(); throw new RuntimeException('Redirect followed'); } catch (ApiError $error) { if ($error->httpStatus !== 302) throw $error; }
try { $client->email->getProviders([], ['timeout'=>0.02]); throw new RuntimeException('Timeout missing'); } catch (RequestTimeoutError $error) { if ($error->outcomeUnknown) throw new RuntimeException('Readonly timeout classified as paid'); }
echo "PHP cURL: 61 wire cases, no cookies, no redirects, real timeout passed\n";
