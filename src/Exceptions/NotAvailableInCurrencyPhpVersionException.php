<?php

namespace Codememory\Reflection\Exceptions;

use Exception;
use Throwable;

final class NotAvailableInCurrencyPhpVersionException extends Exception
{
    public function __construct(float $expectedVersion, string $notAvailableFunction, string $inClass, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("The \"{$notAvailableFunction}\" function in the \"{$inClass}\" class is not available in the current version of php. Expected minimum version \"{$expectedVersion}\"", $code, $previous);
    }
}