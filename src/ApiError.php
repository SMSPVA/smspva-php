<?php
declare(strict_types=1);
namespace SMSPVA;
class ApiError extends \RuntimeException
{
    /** @param int|string $apiCode @param array<string,mixed> $response */
    public function __construct(public string $family, public string $operation, public int $httpStatus, public $apiCode, public array $response)
    {
        parent::__construct("SMSPVA {$family} request failed (HTTP {$httpStatus})");
    }
}
