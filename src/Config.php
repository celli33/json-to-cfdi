<?php

declare(strict_types=1);

namespace Celli33\JsonToCfdi;

class Config
{
    public function __construct(
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
}
