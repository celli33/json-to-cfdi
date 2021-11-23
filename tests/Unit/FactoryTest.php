<?php

declare(strict_types=1);

namespace Dufrei\ApiJsonCfdiBridge\Tests\Unit;

use Celli33\JsonToCfdi\Factory;
use Celli33\JsonToCfdi\Tests\TestCase;
use CfdiUtils\CadenaOrigen\DOMBuilder;
use CfdiUtils\CadenaOrigen\SaxonbCliBuilder;
use CfdiUtils\CadenaOrigen\XsltBuilderInterface;
use CfdiUtils\XmlResolver\XmlResolver;
use PHPUnit\Framework\MockObject\MockObject;

final class FactoryTest extends TestCase
{
    /**
     * @param string $xmlResolverPath
     * @testWith ["/resources"]
     *           [""]
     */
    public function test_create_xml_resolver_is_set_up(string $xmlResolverPath): void
    {
        $factory = Factory::create([
            'XMLRESOLVER_PATH' => $xmlResolverPath,
        ]);
        $xmlResolver = $factory->createXmlResolver();
        $this->assertInstanceOf(XmlResolver::class, $xmlResolver);
        $this->assertSame($xmlResolverPath, $xmlResolver->getLocalPath());
    }

    public function test_create_xslt_builder_returns_dombuilder_if_no_sanxonb_is_set(): void
    {
        $factory = Factory::create([]);
        $xsltBuilder = $factory->createXsltBuilder();
        $this->assertInstanceOf(DOMBuilder::class, $xsltBuilder);
    }

    public function test_create_xslt_builder_returns_sanxonb_if_is_set(): void
    {
        $factory = Factory::create([
            'SAXONB_PATH' => $pathSaxonB = '/opt/saxonb',
        ]);
        $xsltBuilder = $factory->createXsltBuilder();
        $this->assertInstanceOf(SaxonbCliBuilder::class, $xsltBuilder);
        /** @var SaxonbCliBuilder $xsltBuilder */
        $this->assertSame($pathSaxonB, $xsltBuilder->getExecutablePath());
    }

    public function test_create_sign_xml_action_respect_dependences(): void
    {
        /** @var XmlResolver&MockObject $xmlResolver */
        $xmlResolver = $this->createMock(XmlResolver::class);
        /** @var XsltBuilderInterface&MockObject $xsltBuilder */
        $xsltBuilder = $this->createMock(XsltBuilderInterface::class);
        $factory = Factory::create([]);
        $action = $factory->createSignXmlAction($xmlResolver, $xsltBuilder);

        $this->assertSame($xmlResolver, $action->getXmlResolver());
        $this->assertSame($xsltBuilder, $action->getXsltBuilder());
    }

    public function test_create_build_cfdi_from_json_action_respect_dependences(): void
    {
        /** @var XmlResolver&MockObject $xmlResolver */
        $xmlResolver = $this->createMock(XmlResolver::class);
        /** @var XsltBuilderInterface&MockObject $xsltBuilder */
        $xsltBuilder = $this->createMock(XsltBuilderInterface::class);

        $factory = Factory::create([]);
        $action = $factory->createBuildCfdiFromJsonAction($xmlResolver, $xsltBuilder);
        $signXmlAction = $action->getSignXmlAction();

        $this->assertSame($xmlResolver, $signXmlAction->getXmlResolver());
        $this->assertSame($xsltBuilder, $signXmlAction->getXsltBuilder());
    }
}
