<?php

declare(strict_types=1);

namespace PhpCfdi\JsonToCfdiBridge\Actions\BuildCfdiFromJson;

use PhpCfdi\JsonToCfdiBridge\Values\Cfdi;
use PhpCfdi\JsonToCfdiBridge\Values\JsonContent;
use PhpCfdi\JsonToCfdiBridge\Values\PreCfdi;
use PhpCfdi\JsonToCfdiBridge\Values\XmlContent;

class CreateCfdiFromJsonResult
{
    public function __construct(
        private JsonContent $json,
        private XmlContent $convertedXml,
        private PreCfdi $preCfdi,
        private Cfdi $cfdi,
    ) {
    }

    public function getJson(): JsonContent
    {
        return $this->json;
    }

    public function getConvertedXml(): XmlContent
    {
        return $this->convertedXml;
    }

    public function getPreCfdi(): PreCfdi
    {
        return $this->preCfdi;
    }

    public function getCfdi(): Cfdi
    {
        return $this->cfdi;
    }
}
