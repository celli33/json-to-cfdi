<?php

declare(strict_types=1);

namespace PhpCfdi\JsonToCfdiBridge\Tests\Unit\PreCfdiSigner;

use PhpCfdi\JsonToCfdiBridge\PreCfdiSigner\PreCfdiSigner;
use PhpCfdi\JsonToCfdiBridge\PreCfdiSigner\UnableToSignXmlException;
use PhpCfdi\JsonToCfdiBridge\Tests\TestCase;
use CfdiUtils\CadenaOrigen\CfdiDefaultLocations;
use CfdiUtils\CadenaOrigen\XsltBuilderInterface;
use CfdiUtils\XmlResolver\XmlResolver;
use DOMDocument;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;

final class PreCfdiSignerTest extends TestCase
{
    private function createSignerWithMockedDependences(DOMDocument $document): PreCfdiSigner
    {
        /** @var XmlResolver&MockObject $xmlResolver */
        $xmlResolver = $this->createMock(XmlResolver::class);
        /** @var XsltBuilderInterface&MockObject $xsltBuilder */
        $xsltBuilder = $this->createMock(XsltBuilderInterface::class);
        return new PreCfdiSigner($document, $xmlResolver, $xsltBuilder);
    }

    public function test_put_all_data(): void
    {
        $rfc = 'AAAA010101AAA';
        $certificateValue = 'CERTIFICADO';
        $certificateNumber = '12345678901234567890';
        $signature = 'SELLO';
        $expected = $this->createXmlDocument(
            <<<XML
                <cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/4"
                  Certificado="$certificateValue"
                  NoCertificado="$certificateNumber"
                  Sello="$signature"
                  >
                <cfdi:Emisor Rfc="$rfc"/>
                </cfdi:Comprobante>
                XML
        );

        $document = $this->createXmlDocument(
            <<<XML
                <cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/4">
                <cfdi:Emisor/>
                </cfdi:Comprobante>
                XML
        );
        /** @var XmlResolver&MockObject $xmlResolver */
        $xmlResolver = $this->createMock(XmlResolver::class);
        /** @var XsltBuilderInterface&MockObject $xsltBuilder */
        $xsltBuilder = $this->createMock(XsltBuilderInterface::class);
        $signer = new PreCfdiSigner($document, $xmlResolver, $xsltBuilder);

        $signer->putCertificateNumber($certificateNumber);
        $signer->putCertificateValue($certificateValue);
        $signer->putIssuerRfc($rfc);
        $signer->putSignatureValue($signature);

        $this->assertEquals($expected, $document);
    }

    public function test_put_on_non_cfdi33(): void
    {
        $rfc = 'AAAA010101AAA';
        $certificateValue = 'CERTIFICADO';
        $certificateNumber = '12345678901234567890';
        $signature = 'SELLO';
        $expected = $this->createXmlDocument(
            <<<XML
                <cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/2">
                <cfdi:Emisor/>
                </cfdi:Comprobante>
                XML
        );

        $document = $this->createXmlDocument(
            <<<XML
                <cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/2">
                <cfdi:Emisor/>
                </cfdi:Comprobante>
                XML
        );
        /** @var XmlResolver&MockObject $xmlResolver */
        $xmlResolver = $this->createMock(XmlResolver::class);
        /** @var XsltBuilderInterface&MockObject $xsltBuilder */
        $xsltBuilder = $this->createMock(XsltBuilderInterface::class);
        $signer = new PreCfdiSigner($document, $xmlResolver, $xsltBuilder);

        $signer->putCertificateNumber($certificateNumber);
        $signer->putCertificateValue($certificateValue);
        $signer->putIssuerRfc($rfc);
        $signer->putSignatureValue($signature);

        $this->assertEquals($expected, $document);
    }

    public function test_put_rfc_fails_when_exists_but_different(): void
    {
        $sourceRfc = 'AAAA010101AAA';
        $differentRfc = 'XXXX010101XXX';
        $document = $this->createXmlDocument(
            <<<XML
                <cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/4">
                <cfdi:Emisor Rfc="$sourceRfc"/>
                </cfdi:Comprobante>
                XML
        );

        $signer = $this->createSignerWithMockedDependences($document);

        $this->expectException(UnableToSignXmlException::class);
        $this->expectExceptionMessage("The issuer RFC on data $sourceRfc is different from CSD $differentRfc");
        $signer->putIssuerRfc($differentRfc);
    }

    public function test_build_source_string(): void
    {
        $remoteXsltLocation = CfdiDefaultLocations::XSLT_40;
        $localXsltLocation = '/resources/fake/location.xsd';
        $sourceString = '||4.4||';

        $document = $this->createXmlDocument(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/4" Version="4.0"/>',
        );

        /** @var XmlResolver&MockObject $xmlResolver */
        $xmlResolver = $this->createMock(XmlResolver::class);
        $xmlResolver->expects($this->once())
            ->method('resolve')
            ->with($remoteXsltLocation)
            ->willReturn($localXsltLocation);

        /** @var XsltBuilderInterface&MockObject $xsltBuilder */
        $xsltBuilder = $this->createMock(XsltBuilderInterface::class);
        $xsltBuilder->expects($this->once())
            ->method('build')
            ->with($document->saveXML(), $localXsltLocation)
            ->willReturn($sourceString);

        $signer = new PreCfdiSigner($document, $xmlResolver, $xsltBuilder);

        $this->assertSame($sourceString, $signer->buildSourceString());
    }

    public function test_build_source_string_exception(): void
    {
        $remoteXsltLocation = CfdiDefaultLocations::XSLT_40;
        $localXsltLocation = '/resources/fake/location.xsd';
        $resultException = new Exception('ups, something went wrong');

        $document = $this->createXmlDocument(
            '<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" Version="3.3"/>',
        );

        /** @var XmlResolver&MockObject $xmlResolver */
        $xmlResolver = $this->createMock(XmlResolver::class);
        $xmlResolver->expects($this->once())
            ->method('resolve')
            ->with($remoteXsltLocation)
            ->willReturn($localXsltLocation);

        /** @var XsltBuilderInterface&MockObject $xsltBuilder */
        $xsltBuilder = $this->createMock(XsltBuilderInterface::class);
        $xsltBuilder->expects($this->once())
            ->method('build')
            ->willThrowException($resultException);

        $signer = new PreCfdiSigner($document, $xmlResolver, $xsltBuilder);

        /** @var UnableToSignXmlException $caughtException */
        $caughtException = $this->catchException(
            fn () => $signer->buildSourceString(),
            UnableToSignXmlException::class,
        );

        $this->assertSame('Unable to build source string', $caughtException->getMessage());
        $this->assertSame($resultException, $caughtException->getPrevious());
    }
}
