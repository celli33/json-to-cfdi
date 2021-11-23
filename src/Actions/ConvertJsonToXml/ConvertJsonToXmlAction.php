<?php

declare(strict_types=1);

namespace Celli33\JsonToCfdi\Actions\ConvertJsonToXml;

use Celli33\JsonToCfdi\JsonToXmlConverter\Converter as SimpleJsonToXmlConverter;
use Celli33\JsonToCfdi\JsonToXmlConverter\ConverterInterface as SimpleJsonToXmlConverterInterface;
use Celli33\JsonToCfdi\JsonToXmlConverter\JsonToXmlConvertException;
use Celli33\JsonToCfdi\Values\JsonContent;
use Celli33\JsonToCfdi\Values\XmlContent;

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
