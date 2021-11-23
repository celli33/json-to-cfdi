<?php

declare(strict_types=1);

namespace PhpCfdi\JsonToCfdiBridge\Tests\Integration\Actions;

use PhpCfdi\JsonToCfdiBridge\Factory;
use PhpCfdi\JsonToCfdiBridge\PreCfdiSigner\UnableToSignXmlException;
use PhpCfdi\JsonToCfdiBridge\StampService\StampServiceInterface;
use PhpCfdi\JsonToCfdiBridge\Tests\Fakes\FakeCsd;
use PhpCfdi\JsonToCfdiBridge\Tests\TestCase;
use PhpCfdi\JsonToCfdiBridge\Values\XmlContent;
use DOMElement;

final class SignXmlActionTest extends TestCase
{
    public function test_sign_put_all_required_information(): void
    {
        $factory = Factory::create();
        $action = $factory->createSignXmlAction();

        $xml = new XmlContent($this->fileContents('converted.xml'));
        $rfc = 'EKU9003173C9';
        $certificateNumber = '30001000000400002434';
        $certificateContents = base64_encode(hash('sha256', 'CERTIFICADO', true)) ?: 'CERTIFICADO';
        $signature = base64_encode(hash('sha256', 'SELLO', true)) ?: 'SELLO';
        $csd = new FakeCsd($rfc, $certificateContents, $certificateNumber, true, true, $signature);

        $result = $action->execute($xml, $csd);
        $document = $result->getPreCfdi()->getXml()->toDocument();
        /** @var DOMElement $comprobante */
        $comprobante = $document->getElementsByTagNameNS('http://www.sat.gob.mx/cfd/3', 'Comprobante')->item(0);
        /** @var DOMElement $emisor */
        $emisor = $document->getElementsByTagNameNS('http://www.sat.gob.mx/cfd/3', 'Emisor')->item(0);

        $this->assertSame($certificateContents, $comprobante->getAttribute('Certificado'));
        $this->assertSame($certificateNumber, $comprobante->getAttribute('NoCertificado'));
        $this->assertSame($signature, $comprobante->getAttribute('Sello'));
        $this->assertSame($rfc, $emisor->getAttribute('Rfc'));
    }

    public function test_sign_with_invalid_csd_type(): void
    {
        $factory = Factory::create();
        $action = $factory->createSignXmlAction();

        $xml = new XmlContent($this->fileContents('converted.xml'));
        $rfc = 'EKU9003173C9';
        $certificateNumber = '30001000000400002434';
        $csd = new FakeCsd($rfc, 'MIIFuzCCA6...', $certificateNumber, false, true);

        $this->expectException(UnableToSignXmlException::class);
        $this->expectExceptionMessage("The certificate $certificateNumber from $rfc is not a CSD");
        $action->execute($xml, $csd);
    }

    public function test_sign_with_invalid_csd_expired(): void
    {
        $factory = Factory::create();
        $action = $factory->createSignXmlAction();

        $xml = new XmlContent($this->fileContents('converted.xml'));
        $rfc = 'EKU9003173C9';
        $certificateNumber = '30001000000400002434';
        $csd = new FakeCsd($rfc, 'MIIFuzCCA6...', $certificateNumber, true, false);

        $this->expectException(UnableToSignXmlException::class);
        $this->expectExceptionMessage("The certificate $certificateNumber from $rfc is expired");
        $action->execute($xml, $csd);
    }
}
