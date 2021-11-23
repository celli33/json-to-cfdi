<?php

declare(strict_types=1);

namespace PhpCfdi\JsonToCfdiBridge\Tests\Unit;

use PhpCfdi\JsonToCfdiBridge\Config;
use PhpCfdi\JsonToCfdiBridge\StampService\StampServiceInterface;
use PhpCfdi\JsonToCfdiBridge\Tests\TestCase;

final class ConfigTest extends TestCase
{
    public function test_build_config(): void
    {
        /** @var StampServiceInterface $stampService */
        $stampService = $this->createMock(StampServiceInterface::class);
        $xmlResolverPath = '/path';
        $xsltBuilderSaxonPath = '/opt/saxonb';

        $config = new Config($stampService, $xmlResolverPath, $xsltBuilderSaxonPath);

        $this->assertSame($xmlResolverPath, $config->getXmlResolverPath());
        $this->assertSame($xsltBuilderSaxonPath, $config->getXsltBuilderSaxonPath());
        $this->assertSame($stampService, $config->getStampService());
    }
}
