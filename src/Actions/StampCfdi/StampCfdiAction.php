<?php

declare(strict_types=1);

namespace PhpCfdi\JsonToCfdiBridge\Actions\StampCfdi;

use PhpCfdi\JsonToCfdiBridge\StampService\ServiceException;
use PhpCfdi\JsonToCfdiBridge\StampService\StampException;
use PhpCfdi\JsonToCfdiBridge\StampService\StampServiceInterface;
use PhpCfdi\JsonToCfdiBridge\Values\XmlContent;

class StampCfdiAction
{
    public function __construct(private StampServiceInterface $stampService)
    {
    }

    public function getStampService(): StampServiceInterface
    {
        return $this->stampService;
    }

    /**
     * @throws StampException
     * @throws ServiceException
     */
    public function execute(XmlContent $preCfdi): StampCfdiResult
    {
        $cfdi = $this->stampService->stamp($preCfdi);
        return new StampCfdiResult($cfdi);
    }
}
