<?php
declare(strict_types=1);
namespace SMSPVA;
final class Client
{
    public Activation $activation;
    public Rental $rental;
    public Email $email;
    /** @param array{timeout?:float|int,activationBaseUrl?:string,rentalBaseUrl?:string,emailBaseUrl?:string,transport?:callable} $options */
    public function __construct(?string $apiKey = null, array $options = [])
    {
        $transport = new Transport($apiKey, $options);
        $this->activation = new Activation($transport);
        $this->rental = new Rental($transport);
        $this->email = new Email($transport);
    }
    public static function isPending(array $value): bool
    {
        return ($value['statusCode'] ?? null) === 202 && is_array($value['error'] ?? null) && ($value['error']['type'] ?? null) === 'SMS_NOT_YET_RECEIVED';
    }
    public static function isNoStock(array $value): bool { return ($value['status'] ?? null) === 2; }
}
