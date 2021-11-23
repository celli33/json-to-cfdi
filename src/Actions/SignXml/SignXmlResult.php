<?php

declare(strict_types=1);

namespace Celli33\JsonToCfdi\Actions\SignXml;

use Celli33\JsonToCfdi\Values\PreCfdi;

class SignXmlResult
{
    public function __construct(private PreCfdi $preCfdi)
    {
    }

    public function getPreCfdi(): PreCfdi
    {
        return $this->preCfdi;
    }
}
