<?php

declare(strict_types=1);

namespace Celli33\JsonToCfdi\Values;

use Celli33\JsonToCfdi\Values\Base\StringValueObject;
use DOMDocument;

final class XmlContent extends StringValueObject
{
    public function toDocument(): DOMDocument
    {
        $document = new DOMDocument();
        $document->preserveWhiteSpace = false;
        $document->formatOutput = true;
        $document->loadXML($this->value);
        return $document;
    }
}
