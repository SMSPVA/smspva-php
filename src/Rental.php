<?php
declare(strict_types=1);
namespace SMSPVA;
// Generated from sdk/contracts.json. Run scripts/generate.mjs in this workspace.
final class Rental
{
    public function __construct(private Transport $transport) {}

    /**
     * GET /api/rent.php?method=getcountries
     * @param array{} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getCountries(array $params = [], array $options = []): array
    {
        return $this->transport->request('rental.getCountries', $params, $options);
    }

    /**
     * GET /api/rent.php?method=get_default_services
     * @param array{} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getDefaultServices(array $params = [], array $options = []): array
    {
        return $this->transport->request('rental.getDefaultServices', $params, $options);
    }

    /**
     * GET /api/rent.php?method=get_country_by_service
     * @param array{service:string,dtype:string,dcount?:int|null} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getCountriesByService(array $params = [], array $options = []): array
    {
        return $this->transport->request('rental.getCountriesByService', $params, $options);
    }

    /**
     * GET /api/rent.php?method=getdata
     * @param array{country:string,dtype?:string|null,dcount?:int|null,extend?:string|null} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getData(array $params = [], array $options = []): array
    {
        return $this->transport->request('rental.getData', $params, $options);
    }

    /**
     * GET /api/rent.php?method=getdataWithProviders
     * @param array{country:string,dtype?:string|null,dcount?:int|null,extend?:string|null} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getDataWithProviders(array $params = [], array $options = []): array
    {
        return $this->transport->request('rental.getDataWithProviders', $params, $options);
    }

    /**
     * GET /api/rent.php?method=getcount
     * @param array{country:string,dtype?:string|null,dcount?:int|null} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getCount(array $params = [], array $options = []): array
    {
        return $this->transport->request('rental.getCount', $params, $options);
    }

    /**
     * GET /api/rent.php?method=getCountWithProviders
     * @param array{country:string,dtype?:string|null,dcount?:int|null} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getCountWithProviders(array $params = [], array $options = []): array
    {
        return $this->transport->request('rental.getCountWithProviders', $params, $options);
    }

    /**
     * GET /api/rent.php?method=get_count_multi
     * @param array{country:string,services:string,dtype:string,dcount:int} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getMultiServiceCount(array $params = [], array $options = []): array
    {
        return $this->transport->request('rental.getMultiServiceCount', $params, $options);
    }

    /**
     * GET /api/rent.php?method=get_days_canada_paypal
     * @param array{} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getCanadaPaypalDays(array $params = [], array $options = []): array
    {
        return $this->transport->request('rental.getCanadaPaypalDays', $params, $options);
    }

    /**
     * GET /api/rent.php?method=get_min_paypal_days
     * @param array{country:string,service:string} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getMinimumPaypalDays(array $params = [], array $options = []): array
    {
        return $this->transport->request('rental.getMinimumPaypalDays', $params, $options);
    }

    /**
     * GET /api/rent.php?method=create
     * @param array{dtype:string,dcount:int,country:string,service:string,provider?:string|null,partner_key?:string|null} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function create(array $params = [], array $options = []): array
    {
        return $this->transport->request('rental.create', $params, $options);
    }

    /**
     * GET /api/rent.php?method=create_multi
     * @param array{dtype:string,dcount:int,country:string,services:string,provider?:string|null,partner_key?:string|null} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function createMulti(array $params = [], array $options = []): array
    {
        return $this->transport->request('rental.createMulti', $params, $options);
    }

    /**
     * GET /api/rent.php?method=add_service_to_order
     * @param array{id:int,service:string,pnumber:string,rent_days?:int|null} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function addServiceToOrder(array $params = [], array $options = []): array
    {
        return $this->transport->request('rental.addServiceToOrder', $params, $options);
    }

    /**
     * GET /api/rent.php?method=orders
     * @param array{} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getOrders(array $params = [], array $options = []): array
    {
        return $this->transport->request('rental.getOrders', $params, $options);
    }

    /**
     * GET /api/rent.php?method=activate
     * @param array{id:int} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function activate(array $params = [], array $options = []): array
    {
        return $this->transport->request('rental.activate', $params, $options);
    }

    /**
     * GET /api/rent.php?method=sms
     * @param array{id:int} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getSms(array $params = [], array $options = []): array
    {
        return $this->transport->request('rental.getSms', $params, $options);
    }

    /**
     * GET /api/rent.php?method=prolong
     * @param array{id:int,dtype:string,dcount:int} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function prolong(array $params = [], array $options = []): array
    {
        return $this->transport->request('rental.prolong', $params, $options);
    }

    /**
     * GET /api/rent.php?method=prolong_max
     * @param array{id:int,dtype:string,dcount:int} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function prolongByDays(array $params = [], array $options = []): array
    {
        return $this->transport->request('rental.prolongByDays', $params, $options);
    }

    /**
     * GET /api/rent.php?method=delete
     * @param array{id:int} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function deleteOrder(array $params = [], array $options = []): array
    {
        return $this->transport->request('rental.deleteOrder', $params, $options);
    }

    /**
     * GET /api/rent.php?method=get_rent_history
     * @param array{skip?:int|null,take?:int|null} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getHistory(array $params = [], array $options = []): array
    {
        return $this->transport->request('rental.getHistory', $params, $options);
    }

    /**
     * GET /api/rent.php?method=restore_user_precalc
     * @param array{id:int} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getRestoreCost(array $params = [], array $options = []): array
    {
        return $this->transport->request('rental.getRestoreCost', $params, $options);
    }

    /**
     * GET /api/rent.php?method=restore_user
     * @param array{id:int} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function restore(array $params = [], array $options = []): array
    {
        return $this->transport->request('rental.restore', $params, $options);
    }
}
