<?php

declare(strict_types=1);

namespace Celli33\JsonToCfdi\JsonToXmlConverter;

use Stringable;

interface ConverterInterface
{
    /**
     * @throws JsonToXmlConvertException
     */
    public function convert(Stringable|string $json): string;
}
