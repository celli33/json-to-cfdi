<?php

declare(strict_types=1);

namespace Celli33\JsonToCfdi\Tests\Integration\Actions;

use Celli33\JsonToCfdi\Factory;
use Celli33\JsonToCfdi\Tests\TestCase;
use Celli33\JsonToCfdi\Values\JsonContent;
use Celli33\JsonToCfdi\Values\PreCfdi;
use Celli33\JsonToCfdi\Values\SourceString;
use Celli33\JsonToCfdi\Values\XmlContent;

final class BuildCfdiFromJsonActionTest extends TestCase
{
    public function test_build_cfdi_from_json_action(): void
    {
        $jsonContent = new JsonContent($this->fileContents('invoice.json'));
        $convertedContent = new XmlContent($this->fileContents('converted.xml'));
        $sourceStringContent = new SourceString($this->fileContents('sourcestring.txt'));
        $signedContent = new XmlContent($this->fileContents('signed.xml'));

        $factory = Factory::create();
        $xmlResolver = $factory->createXmlResolver();
        $xsltBuilder = $factory->createXsltBuilder();
        $action = $factory->createBuildCfdiFromJsonAction($xmlResolver, $xsltBuilder);
        $result = $action->execute($jsonContent, $this->createCsdForTesting());

        $this->assertSame($jsonContent, $result->getJson());

        $this->assertEquals($convertedContent->toDocument(), $result->getConvertedXml()->toDocument());

        $this->assertEquals($sourceStringContent, $result->getPreCfdi()->getSourceString());

        $this->assertEquals($signedContent->toDocument(), $result->getPreCfdi()->getXml()->toDocument());
    }
}
