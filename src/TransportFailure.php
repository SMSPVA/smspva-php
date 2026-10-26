<?php
declare(strict_types=1);
namespace SMSPVA;
/** Injectable transports may throw this without including request URLs or keys. */
class TransportFailure extends \RuntimeException
{
    public function __construct(public string $kind = 'network') { parent::__construct('HTTP transport failed'); }
}
