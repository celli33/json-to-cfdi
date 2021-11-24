<?php

declare(strict_types=1);

namespace PhpCfdi\JsonToCfdiBridge;

use PhpCfdi\JsonToCfdiBridge\Actions\BuildCfdiFromJson\BuildCfdiFromJsonAction;
use PhpCfdi\JsonToCfdiBridge\Actions\ConvertJsonToXml\ConvertJsonToXmlAction;
use PhpCfdi\JsonToCfdiBridge\Actions\SignXml\SignXmlAction;
use PhpCfdi\JsonToCfdiBridge\Actions\StampCfdi\StampCfdiAction;
use PhpCfdi\JsonToCfdiBridge\StampService\StampServiceInterface;
use CfdiUtils\CadenaOrigen\DOMBuilder;
use CfdiUtils\CadenaOrigen\XsltBuilderInterface;
use CfdiUtils\XmlResolver\XmlResolver;
use CfdiUtils\CadenaOrigen\SaxonbCliBuilder;
use PhpCfdi\JsonToCfdiBridge\Actions\BuildPreCfdiFromJson\BuildPreCfdiFromJsonAction;

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
        string $xmlResolverAbsolutPath = '',
        string $saxonbAbsolutPath = ''
    ): self {
        $config = new Config($xmlResolverAbsolutPath, $saxonbAbsolutPath);
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

    public function createBuildPreCfdiFromJsonAction(
        ?XmlResolver $xmlResolver = null,
        ?XsltBuilderInterface $xsltBuilder = null,
    ): BuildPreCfdiFromJsonAction {
        return new BuildPreCfdiFromJsonAction(
            new ConvertJsonToXmlAction(),
            $this->createSignXmlAction($xmlResolver, $xsltBuilder),
        );
    }
}
