<?php

declare(strict_types=1);

namespace PhpCfdi\JsonToCfdiBridge\StampService;

use PhpCfdi\JsonToCfdiBridge\StampService\ServiceException;
use PhpCfdi\JsonToCfdiBridge\StampService\StampException;
use PhpCfdi\JsonToCfdiBridge\Values\Cfdi;
use PhpCfdi\JsonToCfdiBridge\Values\XmlContent;

interface StampServiceInterface
{
     /**
     * @throws StampException
     * @throws ServiceException
     */
    public function stamp(XmlContent $preCfdi): Cfdi;
}
