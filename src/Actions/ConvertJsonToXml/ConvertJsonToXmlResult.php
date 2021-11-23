<?php

declare(strict_types=1);

namespace PhpCfdi\JsonToCfdiBridge\Actions\ConvertJsonToXml;

use PhpCfdi\JsonToCfdiBridge\Values\XmlContent;

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
