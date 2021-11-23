<?php

declare(strict_types=1);

namespace Celli33\JsonToCfdi\Tests\Unit;

use Celli33\JsonToCfdi\ConfigBuilder;
use Celli33\JsonToCfdi\Tests\TestCase;

final class ConfigBuilderTest extends TestCase
{
    public function test_build_with_no_environment(): void
    {
        $builder = new ConfigBuilder();
        $config = $builder->build();

        $this->assertSame('', $config->getXmlResolverPath());
        $this->assertSame('', $config->getXsltBuilderSaxonPath());
    }

    public function test_build_with_data(): void
    {
        $builder = new ConfigBuilder('/resources', '/opt/saxon/saxonb');
        $config = $builder->build();

        $this->assertSame('/resources', $config->getXmlResolverPath());
        $this->assertSame('/opt/saxon/saxonb', $config->getXsltBuilderSaxonPath());
    }

    public function test_xml_resolver_path_uses_absolute(): void
    {
        $path = '/absolute/path';

        $config = (new ConfigBuilder($path))->build();

        $this->assertSame($path, $config->getXmlResolverPath());
    }

    public function test_xslt_builder_saxon_path_uses_absolute(): void
    {
        $path = '/absolute/path';

        $config = (new ConfigBuilder('', $path))->build();

        $this->assertSame($path, $config->getXsltBuilderSaxonPath());
    }
}
