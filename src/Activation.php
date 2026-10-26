<?php
declare(strict_types=1);
namespace SMSPVA;
// Generated from sdk/contracts.json. Run scripts/generate.mjs in this workspace.
final class Activation
{
    public function __construct(private Transport $transport) {}

    /**
     * GET /activation/balance
     * @param array{} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getBalance(array $params = [], array $options = []): array
    {
        return $this->transport->request('activation.getBalance', $params, $options);
    }

    /**
     * GET /activation/userinfo
     * @param array{} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getUserInfo(array $params = [], array $options = []): array
    {
        return $this->transport->request('activation.getUserInfo', $params, $options);
    }

    /**
     * GET /activation/operators/{country}
     * @param array{country:string} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getOperators(array $params = [], array $options = []): array
    {
        return $this->transport->request('activation.getOperators', $params, $options);
    }

    /**
     * GET /activation/conversions/{service}
     * @param array{service:string} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getConversions(array $params = [], array $options = []): array
    {
        return $this->transport->request('activation.getConversions', $params, $options);
    }

    /**
     * GET /activation/countryservicesettings
     * @param array{} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getMessageTypeSettings(array $params = [], array $options = []): array
    {
        return $this->transport->request('activation.getMessageTypeSettings', $params, $options);
    }

    /**
     * GET /activation/countnumbers
     * @param array{} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getAllNumberCounts(array $params = [], array $options = []): array
    {
        return $this->transport->request('activation.getAllNumberCounts', $params, $options);
    }

    /**
     * GET /activation/countnumbers/{country}
     * @param array{country:string} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getCountryNumberCounts(array $params = [], array $options = []): array
    {
        return $this->transport->request('activation.getCountryNumberCounts', $params, $options);
    }

    /**
     * GET /activation/serviceprice/{country}/{service}
     * @param array{country:string,service:string,voice?:int|null} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getServicePrice(array $params = [], array $options = []): array
    {
        return $this->transport->request('activation.getServicePrice', $params, $options);
    }

    /**
     * GET /activation/serviceprice/{country}
     * @param array{country:string,voice?:int|null} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getCountryOperatorPrices(array $params = [], array $options = []): array
    {
        return $this->transport->request('activation.getCountryOperatorPrices', $params, $options);
    }

    /**
     * GET /activation/serviceprices/{service}
     * @param array{service:string,voice?:int|null} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getServicePrices(array $params = [], array $options = []): array
    {
        return $this->transport->request('activation.getServicePrices', $params, $options);
    }

    /**
     * GET /activation/servicesprices
     * @param array{voice?:int|null} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getAllServicePrices(array $params = [], array $options = []): array
    {
        return $this->transport->request('activation.getAllServicePrices', $params, $options);
    }

    /**
     * GET /activation/number/{country}/{service}
     * @param array{country:string,service:string,voice?:int|null} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getNumber(array $params = [], array $options = []): array
    {
        return $this->transport->request('activation.getNumber', $params, $options);
    }

    /**
     * GET /activation/number/{country}/{service}/{operator}
     * @param array{country:string,service:string,operator:string,voice?:int|null} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getNumberByOperator(array $params = [], array $options = []): array
    {
        return $this->transport->request('activation.getNumberByOperator', $params, $options);
    }

    /**
     * GET /activation/sms/{orderid}
     * @param array{partnerkey?:string|null,orderid:int} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function receiveSms(array $params = [], array $options = []): array
    {
        return $this->transport->request('activation.receiveSms', $params, $options);
    }

    /**
     * GET /activation/orders
     * @param array{} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getOrders(array $params = [], array $options = []): array
    {
        return $this->transport->request('activation.getOrders', $params, $options);
    }

    /**
     * GET /activation/numberstatus/{number}/{service}
     * @param array{number:string,service:string} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getNumberStatus(array $params = [], array $options = []): array
    {
        return $this->transport->request('activation.getNumberStatus', $params, $options);
    }

    /**
     * PUT /activation/clearsms/{orderid}
     * @param array{orderid:int} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function clearSms(array $params = [], array $options = []): array
    {
        return $this->transport->request('activation.clearSms', $params, $options);
    }

    /**
     * PUT /activation/stopsms/{orderid}
     * @param array{orderid:int} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function stopSms(array $params = [], array $options = []): array
    {
        return $this->transport->request('activation.stopSms', $params, $options);
    }

    /**
     * PUT /activation/cancelorder/{orderid}
     * @param array{orderid:int} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function cancelOrder(array $params = [], array $options = []): array
    {
        return $this->transport->request('activation.cancelOrder', $params, $options);
    }

    /**
     * PUT /activation/blocknumber/{orderid}
     * @param array{orderid:int} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function blockNumber(array $params = [], array $options = []): array
    {
        return $this->transport->request('activation.blockNumber', $params, $options);
    }

    /** Saved Unix expiry in seconds. Never creates or cancels an order. */
    public function waitForSms(int $orderid, float $expiresAt, float $interval = 5.0, ?string $partnerkey = null): array
    {
        return $this->transport->waitForSms($orderid, $expiresAt, $interval, $partnerkey);
    }
}
