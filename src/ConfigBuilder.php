<?php

declare(strict_types=1);

namespace Celli33\JsonToCfdi;

class ConfigBuilder
{
    private string $basePath;

    /** @var array<string, mixed> */
    private array $values;

    /** @param array<string, mixed> $environment */
    public function __construct(array $environment)
    {
        $this->basePath = dirname(__DIR__);
        $this->values = $environment;
    }

    public function build(): Config
    {
        return new Config(
            $this->buildPath($this->getValueAsString('XMLRESOLVER_PATH')),
            $this->buildPath($this->getValueAsString('SAXONB_PATH')),
        );
    }

    private function getValueAsString(string $key): string
    {
        $value = $this->values[$key] ?? '';
        if (! is_string($value)) {
            $value = (is_scalar($value)) ? strval($value) : null;
        }
        return $value ?? '';
    }

    private function buildPath(string $path): string
    {
        if ('' === $path) {
            return '';
        }
        if (str_starts_with($path, DIRECTORY_SEPARATOR)) {
            return $path;
        }
        return $this->basePath . DIRECTORY_SEPARATOR . $path;
    }
}
