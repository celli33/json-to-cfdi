<?php

declare(strict_types=1);

namespace Celli33\JsonToCfdi;

class ConfigBuilder
{
    public function __construct(private string $xmlResolverAbsolutPath = '', private string $saxonbAbsolutPath = '')
    {
    }

    public function build(): Config
    {
        return new Config($this->xmlResolverAbsolutPath, $this->saxonbAbsolutPath);
    }
}
