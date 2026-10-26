<?php
declare(strict_types=1);
namespace SMSPVA;
class TransportError extends \RuntimeException
{
    public function __construct(public string $family, public string $operation, public bool $outcomeUnknown, public string $kind = 'network')
    {
        parent::__construct("SMSPVA {$family} request {$kind}; no automatic retry");
    }
}
