<?php

declare(strict_types=1);

namespace Celli33\JsonToCfdi;

use Celli33\JsonToCfdi\Actions\BuildCfdiFromJson\BuildCfdiFromJsonAction;
use Celli33\JsonToCfdi\Actions\ConvertJsonToXml\ConvertJsonToXmlAction;
use Celli33\JsonToCfdi\Actions\SignXml\SignXmlAction;
use Celli33\JsonToCfdi\Actions\StampCfdi\StampCfdiAction;
use Celli33\JsonToCfdi\StampService\StampServiceInterface;
use CfdiUtils\CadenaOrigen\DOMBuilder;
use CfdiUtils\CadenaOrigen\SaxonbCliBuilder;
use CfdiUtils\CadenaOrigen\XsltBuilderInterface;
use CfdiUtils\XmlResolver\XmlResolver;

class Factory
{
    final public function __construct(
        private Config $config,
    ) {
    }

    /**
     * @return static
     */
    public static function create(
        StampServiceInterface $stampService,
        string $xmlResolverAbsolutPath = '',
        string $saxonbAbsolutPath = ''
    ): self {
        $config = new Config($stampService, $xmlResolverAbsolutPath, $saxonbAbsolutPath);
        return new static($config);
    }

    public function createXmlResolver(): XmlResolver
    {
        return new XmlResolver(
            $this->config->getXmlResolverPath(),
        );
    }

    public function createXsltBuilder(): XsltBuilderInterface
    {
        $xsltBuilderSaxonPath = $this->config->getXsltBuilderSaxonPath();
        if ('' !== $xsltBuilderSaxonPath) {
            return new SaxonbCliBuilder($xsltBuilderSaxonPath);
        }
        return new DOMBuilder();
    }

    public function createSignXmlAction(
        ?XmlResolver $xmlResolver = null,
        ?XsltBuilderInterface $xsltBuilder = null,
    ): SignXmlAction {
        $xmlResolver ??= $this->createXmlResolver();
        $xsltBuilder ??= $this->createXsltBuilder();
        return new SignXmlAction($xmlResolver, $xsltBuilder);
    }

    public function createStampCfdiAction(
        StampServiceInterface $stampService
    ): StampCfdiAction {
        return new StampCfdiAction($stampService);
    }

    public function createBuildCfdiFromJsonAction(
        StampServiceInterface $stampService,
        ?XmlResolver $xmlResolver = null,
        ?XsltBuilderInterface $xsltBuilder = null,
    ): BuildCfdiFromJsonAction {
        return new BuildCfdiFromJsonAction(
            new ConvertJsonToXmlAction(),
            $this->createSignXmlAction($xmlResolver, $xsltBuilder),
            $this->createStampCfdiAction($stampService),
        );
    }
}
