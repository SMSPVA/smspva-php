<?php
declare(strict_types=1);
namespace SMSPVA;

final class Transport
{
    private array $operations;
    private array $baseUrls = [];
    private $http;
    public float $timeout;

    public function __construct(private ?string $apiKey = null, array $options = [])
    {
        if ($apiKey !== null && ($apiKey === '' || preg_match('/[\r\n]/', $apiKey))) throw new ValidationError('apiKey must be nonempty and contain no line breaks');
        $this->timeout = self::timeout($options['timeout'] ?? 30.0);
        foreach (['activation', 'rental', 'email'] as $family) {
            if (isset($options[$family . 'BaseUrl'])) $this->baseUrls[$family] = self::baseUrl($options[$family . 'BaseUrl']);
        }
        $this->http = $options['transport'] ?? [self::class, 'curlRequest'];
        if (!is_callable($this->http)) throw new ValidationError('transport must be callable');
        $this->operations = json_decode(file_get_contents(__DIR__ . '/../resources/contracts.json'), true, 512, JSON_THROW_ON_ERROR)['operations'];
    }

    public static function timeout($value): float
    {
        if ((!is_int($value) && !is_float($value)) || !is_finite((float)$value) || $value <= 0 || $value > 2147483.647) throw new ValidationError('timeout must be finite, positive and at most 2147483.647 seconds');
        return (float)$value;
    }
    private static function baseUrl($value): string
    {
        if (!is_string($value) || preg_match('/[\r\n]/', $value)) throw new ValidationError('Invalid SDK base URL');
        $parsed = parse_url($value);
        if (!$parsed || !in_array($parsed['scheme'] ?? '', ['http','https'], true) || !isset($parsed['host']) || isset($parsed['user']) || isset($parsed['pass']) || isset($parsed['query']) || isset($parsed['fragment'])) throw new ValidationError('Base URL must use HTTP(S) without credentials, query or fragment');
        return rtrim($value, '/');
    }
    /** @return array{status:int,body:string} */
    public static function curlRequest(string $url, string $method, array $headers, float $timeout): array
    {
        if (!extension_loaded('curl')) throw new TransportFailure();
        $handle = curl_init($url);
        if ($handle === false) throw new TransportFailure();
        try {
            curl_setopt_array($handle, [CURLOPT_RETURNTRANSFER => true, CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_HTTPHEADER => array_map(static fn($name, $value) => $name . ': ' . $value, array_keys($headers), array_values($headers)),
                CURLOPT_FOLLOWLOCATION => false, CURLOPT_MAXREDIRS => 0, CURLOPT_TIMEOUT_MS => max(1, (int)ceil($timeout * 1000)),
                CURLOPT_CONNECTTIMEOUT_MS => max(1, (int)ceil($timeout * 1000)), CURLOPT_SSL_VERIFYPEER => true, CURLOPT_SSL_VERIFYHOST => 2]);
            $body = curl_exec($handle);
            if ($body === false) throw new TransportFailure(curl_errno($handle) === CURLE_OPERATION_TIMEDOUT ? 'timeout' : 'network');
            return ['status' => (int)curl_getinfo($handle, CURLINFO_RESPONSE_CODE), 'body' => $body];
        } finally { curl_close($handle); }
    }
    /** @param array<string,mixed> $params @param array{timeout?:float|int,useApiKey?:bool} $options @return array<string,mixed> */
    public function request(string $operation, array $params = [], array $options = []): array
    {
        $op = $this->operations[$operation] ?? null;
        if (!$op) throw new ValidationError('Unknown SDK operation');
        $timeout = self::timeout($options['timeout'] ?? $this->timeout);
        if (isset($options['useApiKey']) && !is_bool($options['useApiKey'])) throw new ValidationError('useApiKey must be boolean');
        $allowed = array_column($op['params'], 'name');
        foreach ($params as $name => $value) if (!in_array($name, $allowed, true)) throw new ValidationError('Unexpected request parameter');
        $headers = ['Accept' => 'application/json'];
        $query = isset($op['action']) ? ['method' => $op['action']] : [];
        $path = $op['path'];
        foreach ($op['params'] as $parameter) {
            $name = $parameter['name']; $value = $params[$name] ?? null;
            if ($value === null || (($parameter['omitEmpty'] ?? false) && $value === '')) {
                if ($parameter['required']) throw new ValidationError('Missing required parameter: ' . $name);
                continue;
            }
            $schema = $parameter['schema']; $type = $schema['type'] ?? '';
            if (($type === 'integer' && !is_int($value)) || ($type === 'string' && !is_string($value)) || ($type === 'number' && ((!is_int($value) && !is_float($value)) || !is_finite((float)$value))) || ($type === 'boolean' && !is_bool($value))) throw new ValidationError('Invalid type for parameter: ' . $name);
            if ((isset($schema['enum']) && !in_array($value, $schema['enum'], true)) || (is_numeric($value) && (is_int($value) || is_float($value)) && ((isset($schema['minimum']) && $value < $schema['minimum']) || (isset($schema['maximum']) && $value > $schema['maximum'])))) throw new ValidationError('Invalid value for parameter: ' . $name);
            if (is_string($value)) {
                $length = preg_match_all('/./us', $value);
                if ($length === false || (isset($schema['minLength']) && $length < $schema['minLength']) || (isset($schema['maxLength']) && $length > $schema['maxLength']) || (isset($schema['pattern']) && preg_match('~' . str_replace('~', '\\~', $schema['pattern']) . '~u', $value) !== 1)) throw new ValidationError('Invalid value for parameter: ' . $name);
            }
            $text = is_bool($value) ? ($value ? 'true' : 'false') : (string)$value;
            if ($parameter['in'] === 'path') {
                if ($text === '.' || $text === '..') throw new ValidationError('Invalid path parameter: ' . $name);
                $path = str_replace('{' . $name . '}', rawurlencode($text), $path);
            } elseif ($parameter['in'] === 'header') {
                if (preg_match('/[\r\n]/', $text)) throw new ValidationError('Invalid header: ' . $name);
                $headers[$name] = $text;
            } else $query[$name] = $text;
        }
        if ($op['auth'] === 'required' && $this->apiKey === null) throw new ValidationError('Set apiKey for this operation');
        if ($this->apiKey !== null && $op['auth'] !== 'none' && ($op['auth'] === 'required' || ($options['useApiKey'] ?? true))) {
            if ($op['family'] === 'activation') $headers['apikey'] = $this->apiKey;
            else $query['apikey'] = $this->apiKey;
        }
        $url = ($this->baseUrls[$op['family']] ?? $op['baseUrl']) . $path . ($query ? '?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986) : '');
        try { $response = ($this->http)($url, $op['method'], $headers, $timeout); }
        catch (TransportFailure $e) {
            if ($e->kind === 'timeout') throw new RequestTimeoutError($op['family'], $operation, $op['stateful'], 'timeout');
            throw new TransportError($op['family'], $operation, $op['stateful']);
        } catch (\Throwable $e) { throw new TransportError($op['family'], $operation, $op['stateful']); }
        if (!is_array($response) || !is_int($response['status'] ?? null) || !is_string($response['body'] ?? null)) throw new ProtocolError($op['family'], $operation, 0);
        $httpStatus = $response['status'];
        try {
            $object = json_decode($response['body'], false, 512, JSON_THROW_ON_ERROR);
            if (!$object instanceof \stdClass) throw new \JsonException();
            $body = json_decode($response['body'], true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) { throw new ProtocolError($op['family'], $operation, $httpStatus); }
        $status = $op['family'] === 'activation' ? ($body['statusCode'] ?? null) : ($body['status'] ?? null);
        $error = is_array($body['error'] ?? null) ? $body['error'] : [];
        $code = is_string($error['type'] ?? null) ? $error['type'] : (is_int($body['errorid'] ?? null) ? $body['errorid'] : (is_int($status) ? $status : $httpStatus));
        if ($httpStatus < 200 || $httpStatus >= 300) throw new ApiError($op['family'], $operation, $httpStatus, $code, $body);
        if (!is_int($status)) throw new ProtocolError($op['family'], $operation, $httpStatus);
        if ($op['pending'] && $httpStatus === 202 && $status === 202 && ($error['type'] ?? null) === 'SMS_NOT_YET_RECEIVED') return $body;
        if ($op['noStock'] && $httpStatus === 200 && $status === 2) return $body;
        $errorValue = $object->error ?? null;
        $hasError = $errorValue !== null && $errorValue !== false && $errorValue !== '' && $errorValue !== 0 && $errorValue !== 0.0;
        if ($op['family'] === 'activation' ? ($status !== 200 || $hasError) : $status !== 1) throw new ApiError($op['family'], $operation, $httpStatus, $code, $body);
        return $body;
    }
    public function waitForSms(int $orderid, float $expiresAt, float $interval = 5.0, ?string $partnerkey = null): array
    {
        if (!is_finite($expiresAt) || !is_finite($interval) || $interval < 5) throw new ValidationError('Use a finite expiry and an interval of at least five seconds');
        $deadline = hrtime(true) / 1e9 + ($expiresAt - microtime(true));
        while (($remaining = $deadline - hrtime(true) / 1e9) > 0) {
            $result = $this->request('activation.receiveSms', ['orderid' => $orderid, 'partnerkey' => $partnerkey], ['timeout' => min($this->timeout, $remaining)]);
            if (!Client::isPending($result)) return $result;
            $left = $deadline - hrtime(true) / 1e9;
            usleep((int)max(0, min($interval, $left) * 1e6));
            if ($left <= $interval) throw new PollingTimeoutError();
        }
        throw new PollingTimeoutError();
    }
}
