<?php

declare(strict_types=1);

namespace PhpCfdi\JsonToCfdiBridge\Actions\StampCfdi;

use PhpCfdi\JsonToCfdiBridge\Values\Cfdi;

class StampCfdiResult
{
    public function __construct(private Cfdi $cfdi)
    {
    }

    public function getCfdi(): Cfdi
    {
        return $this->cfdi;
    }
}
