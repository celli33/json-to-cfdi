<?php

declare(strict_types=1);

namespace Celli33\JsonToCfdi\StampService;

use Celli33\JsonToCfdi\Values\Cfdi;
use Celli33\JsonToCfdi\Values\XmlContent;

interface StampServiceInterface
{
    public function stamp(XmlContent $preCfdi): Cfdi;
}
