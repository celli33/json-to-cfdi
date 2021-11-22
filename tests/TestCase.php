<?php

declare(strict_types=1);

namespace Celli33\JsonToCfdi\Tests;

use PHPUnit\Framework\TestCase as Test;

abstract class TestCase extends Test
{
    public static function filePath(string $filename): string
    {
        return __DIR__ . '/_files/' . $filename;
    }

    public static function fileContents(string $filename): string
    {
        return @file_get_contents(static::filePath($filename)) ?: '';
    }
}