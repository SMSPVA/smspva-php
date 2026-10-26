<?php
declare(strict_types=1);
namespace SMSPVA;
// Generated from sdk/contracts.json. Run scripts/generate.mjs in this workspace.
final class Email
{
    public function __construct(private Transport $transport) {}

    /**
     * GET /api/rent_email.php?method=get_providers
     * @param array{} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getProviders(array $params = [], array $options = []): array
    {
        return $this->transport->request('email.getProviders', $params, $options);
    }

    /**
     * GET /api/rent_email.php?method=get_data
     * @param array{pcode?:string|null} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getData(array $params = [], array $options = []): array
    {
        return $this->transport->request('email.getData', $params, $options);
    }

    /**
     * GET /api/rent_email.php?method=get_count
     * @param array{pcode?:string|null} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getCount(array $params = [], array $options = []): array
    {
        return $this->transport->request('email.getCount', $params, $options);
    }

    /**
     * GET /api/rent_email.php?method=create_order
     * @param array{scode:string,pcode?:string|null,dtype:string,dcount:int} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function createOrder(array $params = [], array $options = []): array
    {
        return $this->transport->request('email.createOrder', $params, $options);
    }

    /**
     * GET /api/rent_email.php?method=get_orders
     * @param array{} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getOrders(array $params = [], array $options = []): array
    {
        return $this->transport->request('email.getOrders', $params, $options);
    }

    /**
     * GET /api/rent_email.php?method=get_mails
     * @param array{orderid:int,t?:int|null,s?:int|null,sq?:string|null} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getMails(array $params = [], array $options = []): array
    {
        return $this->transport->request('email.getMails', $params, $options);
    }

    /**
     * GET /api/rent_email.php?method=get_mail_body
     * @param array{orderid:int,emailid:int} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getMailBody(array $params = [], array $options = []): array
    {
        return $this->transport->request('email.getMailBody', $params, $options);
    }

    /**
     * GET /api/rent_email.php?method=buy_alias
     * @param array{orderid:int,aliasid:int} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function buyAlias(array $params = [], array $options = []): array
    {
        return $this->transport->request('email.buyAlias', $params, $options);
    }

    /**
     * GET /api/rent_email.php?method=get_mails_by_alias
     * @param array{orderid:int,aliasid:int,t?:int|null,s?:int|null,sq?:string|null} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getMailsByAlias(array $params = [], array $options = []): array
    {
        return $this->transport->request('email.getMailsByAlias', $params, $options);
    }

    /**
     * GET /api/rent_email.php?method=prolong_order
     * @param array{orderid:int,dtype:string,dcount:int} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function prolongOrder(array $params = [], array $options = []): array
    {
        return $this->transport->request('email.prolongOrder', $params, $options);
    }

    /**
     * GET /api/rent_email.php?method=cancel_order
     * @param array{orderid:int} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function cancelOrder(array $params = [], array $options = []): array
    {
        return $this->transport->request('email.cancelOrder', $params, $options);
    }

    /**
     * GET /api/rent_email.php?method=get_archived_orders
     * @param array{} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function getArchivedOrders(array $params = [], array $options = []): array
    {
        return $this->transport->request('email.getArchivedOrders', $params, $options);
    }

    /**
     * GET /api/rent_email.php?method=calc_restore_order
     * @param array{orderid:int} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function calcRestoreOrder(array $params = [], array $options = []): array
    {
        return $this->transport->request('email.calcRestoreOrder', $params, $options);
    }

    /**
     * GET /api/rent_email.php?method=restore_order
     * @param array{orderid:int} $params
     * @param array{timeout?:float|int,useApiKey?:bool} $options
     * @return array<string,mixed> Original API response; see resources/contracts.json.
     */
    public function restoreOrder(array $params = [], array $options = []): array
    {
        return $this->transport->request('email.restoreOrder', $params, $options);
    }
}
