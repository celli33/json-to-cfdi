<?php

declare(strict_types=1);

namespace Celli33\JsonToCfdi\Actions\StampCfdi;

use Celli33\JsonToCfdi\Values\Cfdi;

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
