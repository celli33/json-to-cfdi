<?php

declare(strict_types=1);

namespace PhpCfdi\JsonToCfdiBridge\Tests;

use PhpCfdi\JsonToCfdiBridge\Values\CredentialCsd;
use PhpCfdi\JsonToCfdiBridge\Values\Csd;
use Closure;
use DOMDocument;
use LogicException;
use PhpCfdi\Credentials\Credential;
use PHPUnit\Framework\TestCase as Test;
use Throwable;

abstract class TestCase extends Test
{

    public static function basePath(string $path = ''): string
    {
        return dirname(__DIR__) . '/' . $path;
    }

    public static function filePath(string $filename): string
    {
        return __DIR__ . '/_files/' . $filename;
    }

    public static function fileContents(string $filename): string
    {
        return @file_get_contents(static::filePath($filename)) ?: '';
    }

    public function createCsdForTesting(): Csd
    {
        return CredentialCsd::createFromFiles(
            $this->filePath('fake-csd/EKU9003173C9.cer'),
            $this->filePath('fake-csd/EKU9003173C9.key'),
            trim($this->fileContents('fake-csd/EKU9003173C9-password.txt')),
        );
    }

    public function createCredentialForTesting(): Credential
    {
        return Credential::openFiles(
            $this->filePath('fake-csd/EKU9003173C9.cer'),
            $this->filePath('fake-csd/EKU9003173C9.key'),
            trim($this->fileContents('fake-csd/EKU9003173C9-password.txt')),
        );
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
