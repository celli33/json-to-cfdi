<?php

declare(strict_types=1);

namespace Celli33\JsonToCfdi\Tests\Unit;

use Celli33\JsonToCfdi\Config;
use Celli33\JsonToCfdi\Tests\TestCase;

final class ConfigTest extends TestCase
{
    public function test_config_values(): void
    {
        $xmlResolverPath = '/path';
        $xsltBuilderSaxonPath = '/opt/saxonb';

        $config = new Config($xmlResolverPath, $xsltBuilderSaxonPath);

        $this->assertSame($xmlResolverPath, $config->getXmlResolverPath());
        $this->assertSame($xsltBuilderSaxonPath, $config->getXsltBuilderSaxonPath());
    }
}
