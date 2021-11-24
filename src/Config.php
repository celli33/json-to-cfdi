<?php

declare(strict_types=1);

namespace PhpCfdi\JsonToCfdiBridge;

class Config
{
    private string $xmlResolverPath;
    private string $xsltBuilderSaxonPath;


    public function __construct(string $xmlResolverPath, string $xsltBuilderSaxonPath)
    {
        $this->xmlResolverPath = $this->buildPath($xmlResolverPath);
        $this->xsltBuilderSaxonPath = $this->buildPath($xsltBuilderSaxonPath);
    }

    public function getXmlResolverPath(): string
    {
        return $this->xmlResolverPath;
    }

    public function getXsltBuilderSaxonPath(): string
    {
        return $this->xsltBuilderSaxonPath;
    }

    private function buildPath(string $path): string
    {
        if ('' === $path) {
            return '';
        }
        if (str_starts_with($path, DIRECTORY_SEPARATOR)) {
            return $path;
        }
        return '/' . $path;
    }
}
