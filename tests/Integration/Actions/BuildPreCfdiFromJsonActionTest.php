<?php

declare(strict_types=1);

namespace PhpCfdi\JsonToCfdiBridge\Tests\Integration\Actions;

use PhpCfdi\JsonToCfdiBridge\Factory;
use PhpCfdi\JsonToCfdiBridge\Tests\Fakes\FakeStampService;
use PhpCfdi\JsonToCfdiBridge\Tests\TestCase;
use PhpCfdi\JsonToCfdiBridge\Values\Cfdi;
use PhpCfdi\JsonToCfdiBridge\Values\JsonContent;
use PhpCfdi\JsonToCfdiBridge\Values\SourceString;
use PhpCfdi\JsonToCfdiBridge\Values\Uuid;
use PhpCfdi\JsonToCfdiBridge\Values\XmlContent;

final class BuildPreCfdiFromJsonActionTest extends TestCase
{
    public function test_build_pre_cfdi_from_json_action_using_fake_service(): void
    {
        $jsonContent = new JsonContent($this->fileContents('invoice.json'));
        $convertedContent = new XmlContent($this->fileContents('converted.xml'));
        $sourceStringContent = new SourceString($this->fileContents('sourcestring.txt'));
        $signedContent = new XmlContent($this->fileContents('signed.xml'));
        $factory = Factory::create($_ENV['XMLRESOLVER_PATH']);
        $action = $factory->createBuildPreCfdiFromJsonAction();
        $result = $action->execute($jsonContent, $this->createCsdForTesting());

        $this->assertSame($jsonContent, $result->getJson());

        $this->assertEquals($convertedContent->toDocument(), $result->getConvertedXml()->toDocument());

        $this->assertEquals($sourceStringContent, $result->getPreCfdi()->getSourceString());

        $this->assertEquals($signedContent->toDocument(), $result->getPreCfdi()->getXml()->toDocument());
    }
}
