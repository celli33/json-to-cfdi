<?php

declare(strict_types=1);

namespace PhpCfdi\JsonToCfdiBridge\StampService;

use PhpCfdi\JsonToCfdiBridge\Values\Cfdi;
use PhpCfdi\JsonToCfdiBridge\Values\XmlContent;

interface StampServiceInterface
{
    public function stamp(XmlContent $preCfdi): Cfdi;
}
