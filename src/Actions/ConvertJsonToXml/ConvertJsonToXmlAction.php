<?php

declare(strict_types=1);

namespace PhpCfdi\JsonToCfdiBridge\Actions\ConvertJsonToXml;

use PhpCfdi\JsonToCfdiBridge\JsonToXmlConverter\Converter as SimpleJsonToXmlConverter;
use PhpCfdi\JsonToCfdiBridge\JsonToXmlConverter\ConverterInterface as SimpleJsonToXmlConverterInterface;
use PhpCfdi\JsonToCfdiBridge\JsonToXmlConverter\JsonToXmlConvertException;
use PhpCfdi\JsonToCfdiBridge\Values\JsonContent;
use PhpCfdi\JsonToCfdiBridge\Values\XmlContent;

class ConvertJsonToXmlAction
{
    private SimpleJsonToXmlConverterInterface $converter;

    public function __construct(SimpleJsonToXmlConverterInterface $converter = null)
    {
        $this->converter = $converter ?? new SimpleJsonToXmlConverter();
    }

    /**
     * @throws JsonToXmlConvertException
     */
    public function execute(JsonContent $json): ConvertJsonToXmlResult
    {
        $contents = $this->converter->convert($json);
        return new ConvertJsonToXmlResult(
            new XmlContent($contents),
        );
    }
}
