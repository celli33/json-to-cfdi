<?php

declare(strict_types=1);

namespace Celli33\JsonToCfdi\Actions\ConvertJsonToXml;

use Celli33\JsonToCfdi\Values\XmlContent;

class ConvertJsonToXmlResult
{
    public function __construct(private XmlContent $xml)
    {
    }

    public function getXml(): XmlContent
    {
        return $this->xml;
    }
}
