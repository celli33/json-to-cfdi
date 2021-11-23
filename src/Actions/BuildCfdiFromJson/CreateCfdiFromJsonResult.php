<?php

declare(strict_types=1);

namespace Celli33\JsonToCfdi\Actions\BuildCfdiFromJson;

use Celli33\JsonToCfdi\Values\Cfdi;
use Celli33\JsonToCfdi\Values\JsonContent;
use Celli33\JsonToCfdi\Values\PreCfdi;
use Celli33\JsonToCfdi\Values\XmlContent;

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
