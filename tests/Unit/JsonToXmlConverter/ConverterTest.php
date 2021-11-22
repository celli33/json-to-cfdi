<?php

declare(strict_types=1);

namespace Celli33\JsonToCfdi\Tests\Unit\JsonToXmlConverter;

use Celli33\JsonToCfdi\JsonToXmlConverter\Converter;
use Celli33\JsonToCfdi\JsonToXmlConverter\ConverterInterface;
use Celli33\JsonToCfdi\JsonToXmlConverter\JsonToXmlConvertException;
use Celli33\JsonToCfdi\Tests\TestCase;

final class ConverterTest extends TestCase
{
    public function test_converter_implements_converter_interface(): void
    {
        $this->assertInstanceOf(ConverterInterface::class, new Converter());
    }

    public function test_convert_simple_structure(): void
    {
        $json = <<<JSON
            {
                "root": {
                    "_attributes": {"id": 1, "class": "main"},
                    "foo": {
                        "_attributes": {"id": 2},
                        "deep": {
                            "_attributes": {"id": 3}
                        }
                    },
                    "bar": [
                        {"_attributes": {"id": 4}},
                        {"_attributes": {"id": 5}}
                    ]
                }
            }
            JSON;
        $expected = $this->createXmlDocument(
            <<<XML
                <root id="1" class="main">
                  <foo id="2">
                    <deep id="3"/>
                  </foo>
                  <bar id="4"/>
                  <bar id="5"/>
                </root>
                XML
        );

        $converter = new Converter();
        $converted = $this->createXmlDocument($converter->convert($json));
        $this->assertEquals($expected, $converted);
    }

    public function test_convert_with_name_spaces(): void
    {
        $json = <<<JSON
            {
                "r:root": {
                    "_attributes": {"xmlns:r": "uri:root", "id": 1, "class": "main"},
                    "r:foo": {
                        "_attributes": {"id": 2},
                        "d:deep": {
                            "_attributes": {"xmlns:d": "uri:deep", "id": 3}
                        }
                    },
                    "r:bar": [
                        {"_attributes": {"id": 4}},
                        {"_attributes": {"id": 5}}
                    ]
                }
            }
            JSON;
        $expected = $this->createXmlDocument(
            <<<XML
                <r:root id="1" class="main" xmlns:r="uri:root">
                  <r:foo id="2">
                    <d:deep id="3"  xmlns:d="uri:deep"/>
                  </r:foo>
                  <r:bar id="4"/>
                  <r:bar id="5"/>
                </r:root>
                XML
        );

        $converter = new Converter();
        $converted = $this->createXmlDocument($converter->convert($json));
        $this->assertEquals($expected, $converted);
    }

    public function test_error_on_invalid_json(): void
    {
        $json = 'invalid json';
        $converter = new Converter();
        $this->expectException(JsonToXmlConvertException::class);
        $this->expectExceptionMessage('Unable to parse JSON');
        $converter->convert($json);
    }

    public function test_error_on_non_object(): void
    {
        $json = '1';
        $converter = new Converter();
        $this->expectException(JsonToXmlConvertException::class);
        $this->expectExceptionMessage('does not contains a collection');
        $converter->convert($json);
    }

    public function test_error_on_array(): void
    {
        $json = '[]';
        $converter = new Converter();
        $this->expectException(JsonToXmlConvertException::class);
        $this->expectExceptionMessage('does not contains a collection');
        $converter->convert($json);
    }

    public function test_error_on_non_unique_root(): void
    {
        $json = '{ "foo": {}, "bar": {} }';
        $converter = new Converter();
        $this->expectException(JsonToXmlConvertException::class);
        $this->expectExceptionMessage('does not contains a unique root element');
        $converter->convert($json);
    }

    public function test_error_on_empty_object(): void
    {
        $json = '{}';
        $converter = new Converter();
        $this->expectException(JsonToXmlConvertException::class);
        $this->expectExceptionMessage('does not contains a unique root element');
        $converter->convert($json);
    }

    public function test_error_on_invalid_node_name(): void
    {
        $json = '{ "": {} }';
        $converter = new Converter();
        $this->expectException(JsonToXmlConvertException::class);
        $this->expectExceptionMessage('Invalid element name on /<empty-node-name>');
        $converter->convert($json);
    }

    public function test_error_on_invalid_node_content(): void
    {
        $json = '{ "root": "" }';
        $converter = new Converter();
        $this->expectException(JsonToXmlConvertException::class);
        $this->expectExceptionMessage('Invalid element content on /root');
        $converter->convert($json);
    }

    public function test_error_on_invalid_node_content_invalid_attributes(): void
    {
        $json = '{"root": {"child": {"_attributes": "foo"}}}';
        $converter = new Converter();
        $this->expectException(JsonToXmlConvertException::class);
        $this->expectExceptionMessage('Invalid element content on /root/child/_attributes');
        $converter->convert($json);
    }
}
