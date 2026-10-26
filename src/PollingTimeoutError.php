<?php
declare(strict_types=1);
namespace SMSPVA;
class PollingTimeoutError extends \RuntimeException
{
    public function __construct() { parent::__construct('No SMS result before the saved order deadline'); }
}
