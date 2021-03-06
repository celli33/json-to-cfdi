<?php

declare(strict_types=1);

namespace PhpCfdi\JsonToCfdiBridge\StampService;

use RuntimeException;
use Throwable;

class StampException extends RuntimeException
{
    /** @var StampErrors */
    private StampErrors $errors;

    public function __construct(string $message, StampErrors $errors, Throwable $previous = null)
    {
        parent::__construct($message, previous: $previous);
        $this->errors = $errors;
    }

    /** @return StampErrors */
    public function getErrors(): StampErrors
    {
        return $this->errors;
    }
}
