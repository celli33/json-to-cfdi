<?php

declare(strict_types=1);

namespace Celli33\JsonToCfdi\StampService;

use Celli33\JsonToCfdi\Values\XmlContent;
use RuntimeException;
use Throwable;

class ServiceException extends RuntimeException
{
    private XmlContent $preCfdi;

    public function __construct(string $string, XmlContent $preCfdi, Throwable $exception)
    {
        parent::__construct($string, previous: $exception);
        $this->preCfdi = $preCfdi;
    }

    public function getPreCfdi(): XmlContent
    {
        return $this->preCfdi;
    }
}
