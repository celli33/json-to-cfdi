<?php

declare(strict_types=1);

namespace PhpCfdi\JsonToCfdiBridge\Actions\SignXml;

use PhpCfdi\JsonToCfdiBridge\Values\PreCfdi;

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
