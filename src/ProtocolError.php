<?php
declare(strict_types=1);
namespace SMSPVA;
class ProtocolError extends \RuntimeException
{
    public function __construct(public string $family, public string $operation, public int $httpStatus)
    {
        parent::__construct("SMSPVA {$family} returned an invalid response (HTTP {$httpStatus})");
    }
}
