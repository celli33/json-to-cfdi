<?php

declare(strict_types=1);

namespace PhpCfdi\JsonToCfdiBridge\JsonToXmlConverter;

use RuntimeException;
use Throwable;

class JsonToXmlConvertException extends RuntimeException
{
    public function __construct(string $message, Throwable $previous = null)
    {
        parent::__construct($message, previous: $previous);
    }
}
