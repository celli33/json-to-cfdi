<?php

declare(strict_types=1);

namespace PhpCfdi\JsonToCfdiBridge;

use PhpCfdi\JsonToCfdiBridge\StampService\StampServiceInterface;

class Config
{
    public function __construct(
        private StampServiceInterface $stampService,
        private string $xmlResolverPath,
        private string $xsltBuilderSaxonPath,
    ) {
    }

    public function getXmlResolverPath(): string
    {
        return $this->xmlResolverPath;
    }

    public function getXsltBuilderSaxonPath(): string
    {
        return $this->xsltBuilderSaxonPath;
    }

    public function getStampService(): StampServiceInterface
    {
        return $this->stampService;
    }
}
