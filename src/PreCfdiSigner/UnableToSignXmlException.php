<?php

declare(strict_types=1);

namespace PhpCfdi\JsonToCfdiBridge\PreCfdiSigner;

use RuntimeException;
use Throwable;

class UnableToSignXmlException extends RuntimeException
{
    public function __construct(string $message, Throwable $previous = null)
    {
        parent::__construct($message, previous: $previous);
    }
}
