<?php

declare(strict_types=1);

namespace Celli33\JsonToCfdi\Tests\Unit;

use Celli33\JsonToCfdi\Config;
use Celli33\JsonToCfdi\StampService\StampServiceInterface;
use Celli33\JsonToCfdi\Tests\TestCase;

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
