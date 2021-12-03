<?php

declare(strict_types=1);

namespace PhpCfdi\JsonToCfdiBridge\Actions\SignXml;

use PhpCfdi\JsonToCfdiBridge\Actions\SignXml\SignXmlResult;
use PhpCfdi\JsonToCfdiBridge\PreCfdiSigner\PreCfdiSigner;
use PhpCfdi\JsonToCfdiBridge\PreCfdiSigner\UnableToSignXmlException;
use PhpCfdi\JsonToCfdiBridge\Values\Csd;
use PhpCfdi\JsonToCfdiBridge\Values\PreCfdi;
use PhpCfdi\JsonToCfdiBridge\Values\SourceString;
use PhpCfdi\JsonToCfdiBridge\Values\XmlContent;
use CfdiUtils\CadenaOrigen\XsltBuilderInterface;
use CfdiUtils\XmlResolver\XmlResolver;
use PhpCfdi\CfdiCleaner\Cleaner;

class SignXmlAction
{
    public function __construct(
        private XmlResolver $xmlResolver,
        private XsltBuilderInterface $xsltBuilder,
    ) {
    }

    public function getXmlResolver(): XmlResolver
    {
        return $this->xmlResolver;
    }

    public function getXsltBuilder(): XsltBuilderInterface
    {
        return $this->xsltBuilder;
    }

    /** @throws UnableToSignXmlException */
    public function execute(XmlContent $xml, Csd $csd): SignXmlResult
    {
        if (! $csd->isCsd()) {
            $message = sprintf('The certificate %s from %s is not a CSD', $csd->getCertificateNumber(), $csd->getRfc());
            throw new UnableToSignXmlException($message);
        }
        if (! $csd->isValid()) {
            $message = sprintf('The certificate %s from %s is expired', $csd->getCertificateNumber(), $csd->getRfc());
            throw new UnableToSignXmlException($message);
        }

        $cleanDocument = $xml->getValue();
        $cleaner = new Cleaner();
        $document = $cleaner->cleanStringToDocument($cleanDocument);

        $signer = new PreCfdiSigner($document, $this->xmlResolver, $this->xsltBuilder);

        $signer->putIssuerRfc($csd->getRfc());
        $signer->putCertificateValue($csd->getCertificateContents());
        $signer->putCertificateNumber($csd->getCertificateNumber());

        $sourceString = new SourceString($signer->buildSourceString());
        $signature = $csd->sign($sourceString);
        $signer->putSignatureValue($signature);

        return new SignXmlResult(
            new PreCfdi(
                new XmlContent($document->saveXML() ?: ''),
                $sourceString,
            ),
        );
    }
}
