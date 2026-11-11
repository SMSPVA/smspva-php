# SMSPVA PHP SDK

Official PHP client for [SMSPVA](https://smspva.com/), a service for receiving verification SMS, renting phone numbers and renting private email addresses. Covers all 61 methods in Activation V2 (25), Rental (22) and Private Email (14). Requires PHP 8.0+ with cURL and JSON. This is a synchronous alpha release. Alternative API is excluded.

## Installation

Install the alpha release with Composer:

```sh
composer require smspva/sdk:^0.1@alpha
```

To pin this release, use `composer require smspva/sdk:0.1.0-alpha.3`. The package has no runtime Composer dependencies beyond PHP and its required extensions.

[Sign in to your SMSPVA account](https://smspva.com/signin.html), open **Profile and ApiKey**, and set **`SMSPVA_API_KEY`** in your server environment. Keep the key out of source control and browser code. This example reads your balance and reference lists; it does not create an order.

```php
<?php
declare(strict_types=1);
require __DIR__ . '/vendor/autoload.php';

use SMSPVA\Client;

$client = new Client(getenv('SMSPVA_API_KEY') ?: null);
print_r($client->activation->getBalance());
print_r($client->rental->getCountries());
print_r($client->email->getProviders());
```

The resource properties are **`activation`**, **`rental`** and **`email`**. Their method names match the JavaScript SDK, such as `getNumber`, `getOrders` and `createOrder`; see the [complete method index](https://github.com/SMSPVA/smspva-php/blob/main/METHODS.md). Each method accepts a parameter array and an optional request-options array. Generated PHPDoc shapes describe the required and optional input fields; runtime validation rejects unknown fields, missing values, wrong scalar types and invalid enum values before sending HTTP.

## References and parameters

Reference methods fetch live data; the SDK contains no fixed country, provider or service lists. Public methods work without an API key:

```php
$public = new Client();
$providers = $public->email->getProviders();
$services = $public->email->getData(['pcode' => 'CODE_FROM_GET_PROVIDERS']);
```

**`pcode`** is optional. An omitted, `null` or empty value is not sent. Choose service and provider codes from the current responses rather than copying a hardcoded list.

Activation V2 uses the **`apikey`** header. Rental and Private Email use the query parameter with that name. Public methods never send the configured key. Optional-key Rental methods can omit it with the second argument `['useApiKey' => false]`. Protected methods still require a key.

All wire parameter names remain unchanged, including **`orderid`**, **`partnerkey`**, **`partner_key`**, **`provider`** and pagination fields. Query and path values are encoded. Rental `getMultiServiceCount` requires **`dtype`** and **`dcount`** explicitly because the documentation warns against relying on backend defaults. The underlying API action stays `get_count_multi`.

## Requests and results

Methods return associative arrays decoded from the original JSON. Field names, price strings, IDs and timestamps are not renamed or normalized. The bundled `resources/contracts.json` records the operation contracts and source hashes; method PHPDoc documents inputs. Responses are documented as `array<string,mixed>` rather than generated response DTO classes.

The following example creates a paid email rental. Run it only when you intend to create that order:

```php
$result = $client->email->createOrder([
    'scode' => 'CODE_FROM_GET_DATA',
    'dtype' => 'week',
    'dcount' => 1,
]);
if (Client::isNoStock($result)) {
    echo "No mailbox available; refresh availability before trying again.\n";
} else {
    print_r($result['data'] ?? $result);
}
```

Email creation **`status=2`** remains a normal no-stock response. Activation `receiveSms` likewise returns the original HTTP **`202`** / **`SMS_NOT_YET_RECEIVED`** pending result; `Client::isPending($result)` recognizes it. These are not converted into generic API errors.

## Waiting for SMS

Save the expiry once when your number is created. This helper only checks the existing order:

```php
// $numberResult is the successful response from your own getNumber request.
$expiresAt = microtime(true) + $numberResult['data']['orderExpireIn'];
$result = $client->activation->waitForSms(
    $numberResult['data']['orderId'],
    $expiresAt
);
```

**`expiresAt`** is Unix seconds. The default interval is five seconds, and shorter intervals are rejected by this helper. It uses a monotonic deadline after converting the supplied expiry, bounds each request by the remaining time, and stops on expiry or the first API/transport error. It never creates or cancels an order. Receiving a new activation SMS can trigger payment.

This PHP client is synchronous. It has no async transport or cancellation token; use the request timeout or a supervisor to bound a request. Terminating a request cannot undo an operation already accepted by SMSPVA.

## Errors and timeouts

```php
use SMSPVA\ApiError;
use SMSPVA\TransportError;

try {
    $balance = $client->activation->getBalance();
} catch (ApiError $error) {
    // Original API body: $error->response
    echo $error->httpStatus . ' ' . $error->apiCode;
} catch (TransportError $error) {
    // $error->kind is network or timeout.
    // Check current orders before repeating when $error->outcomeUnknown is true.
    echo $error->kind;
}
```

`ApiError` retains **`family`**, **`operation`**, **`httpStatus`**, **`apiCode`** and the original **`response`**. `ProtocolError` means invalid JSON or a missing/invalid status envelope. `RequestTimeoutError` extends `TransportError`; **`outcomeUnknown`** flags state-changing/paid requests whose result was not received. `PollingTimeoutError` means the saved expiry passed without a successful SMS result. `ValidationError` means the request was rejected locally.

No request is retried and no redirect is followed. This includes GET operations that create orders or trigger payment. Default timeout is 30 seconds for the whole cURL transfer, including the response body:

```php
$client = new Client(getenv('SMSPVA_API_KEY') ?: null, ['timeout' => 20.0]);
$balance = $client->activation->getBalance([], ['timeout' => 5.0]);
```

Exception messages omit request URLs and keys. Original API bodies and your own HTTP logging may still contain sensitive data; keep credentials and message content out of logs. cURL uses a fresh handle per request and keeps no cookie jar.

For embedding and tests, client options accept **`activationBaseUrl`**, **`rentalBaseUrl`**, **`emailBaseUrl`** and a **`transport`** callable. Custom base URLs must use HTTP(S) without embedded credentials, query or fragment. The callable receives `(string $url, string $method, array $headers, float $timeout)` and returns `['status' => int, 'body' => string]`; it can throw `TransportFailure('timeout')` or `TransportFailure('network')`. It must honor the supplied total timeout, disable redirects and avoid retrying. It owns any proxy configuration.

## Development and validation

From the repository root:

```sh
node scripts/generate.mjs
composer test
node tests/loopback.mjs
```

The generator reads `tests/contracts.json` and builds named methods deterministically; it does not need network access. Tests cover all 61 methods, auth, encoding, input validation, pending/no-stock results, raw errors and polling. The loopback suite also exercises real cURL, no cookies, redirects and timeout behavior. It uses the PHP binary in `SMSPVA_PHP_BINARY`, defaulting to the current Windows installation; adjust its extension arguments for another machine.

The tests do not contact production or buy numbers. Paid production behavior has not been validated. The default transport uses PHP's [cURL API](https://www.php.net/manual/en/function.curl-setopt.php), and response decoding follows PHP's [JSON rules](https://www.php.net/manual/en/function.json-decode.php).

## License

MIT. See [LICENSE](https://github.com/SMSPVA/smspva-php/blob/main/LICENSE).

## Documentation and support

Read the [SMSPVA API documentation](https://docs.smspva.com/) and the [method index](https://github.com/SMSPVA/smspva-php/blob/main/METHODS.md) for parameters, response codes and workflows. SMSPVA supports [phone number rentals](https://smspva.com/rent) and [private email rentals](https://smspva.com/rent-mail.html); availability and prices come from the live API.

For account or billing questions, visit the [SMSPVA help center](https://smspva.com/help.html). Report SDK bugs through [GitHub issues](https://github.com/SMSPVA/smspva-php/issues), without API keys or private message content.

## Compact number counts and operator ratings

Using an already configured client:

```php
$allCounts = $client->activation->getAllNumberCountsCompact();
$uaCounts = $client->activation->getCountryNumberCountsCompact(['country' => 'ua']);
```

Both requests use GET and the account API key in the `apikey` header. Country codes are case-insensitive; an unknown country returns `data: []`. No extra query parameters are required. Results are cached by the API for 60 seconds, independently for all countries and for each country.

The SDK preserves `Operator`, `Country`, `Services`, `Rating` and `ServiceRatings` exactly as returned, including negative counts and missing or invalid ratings. It does not insert computed rating fields. For non-`Donor*` operators, the effective rating is 1. For `Donor*` operators, use a service rating if it is an integer from 1 to 10, then a valid overall rating; otherwise the rating is undefined. Lower is better.

## Receive another SMS on a previously used number

Call the Activation methods in this order: `repeatNumber`, `getPhoneStatus`, `startSms`, `receiveSms`. Use a number previously paid for on the same service and carry the new order ID through the remaining calls. Request a new code in the target service after starting SMS reception.

HTTP 202 with NUMBER_NOT_READY includes retryAfter in seconds. For PHONE_NOT_READY, wait five seconds. Wait 30 seconds between SMS checks while the order is active. Receiving a new paid SMS can incur a charge. If the number request has an uncertain outcome, inspect current orders before retrying.
