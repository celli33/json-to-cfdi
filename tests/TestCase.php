<?php

declare(strict_types=1);

namespace Celli33\JsonToCfdi\Tests;

use Closure;
use DOMDocument;
use LogicException;
use PHPUnit\Framework\TestCase as Test;
use Throwable;

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

    protected function createXmlDocument(string $xml): DOMDocument
    {
        $document = new DOMDocument();
        $document->preserveWhiteSpace = false;
        $document->formatOutput = true;
        $document->loadXML($xml);
        return $document;
    }

    protected function catchException(Closure $test, string $exceptionToCatch, string $fail = ''): Throwable
    {
        try {
            call_user_func($test);
        } catch (Throwable $exception) {
            if ($exception instanceof $exceptionToCatch) {
                return $exception;
            }
        }
        throw new LogicException($fail ?: "Unable to catch the exception $exceptionToCatch");
    }
}
