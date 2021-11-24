<?php

declare(strict_types=1);

namespace PhpCfdi\JsonToCfdiBridge\Actions\BuildPreCfdiFromJson;

use PhpCfdi\JsonToCfdiBridge\Values\JsonContent;
use PhpCfdi\JsonToCfdiBridge\Values\PreCfdi;
use PhpCfdi\JsonToCfdiBridge\Values\XmlContent;

class CreatePreCfdiFromJsonResult
{
    public function __construct(
        private JsonContent $json,
        private XmlContent $convertedXml,
        private PreCfdi $preCfdi,
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
}
