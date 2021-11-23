<?php

declare(strict_types=1);

namespace Celli33\JsonToCfdi\Actions\StampCfdi;

use Celli33\JsonToCfdi\StampService\ServiceException;
use Celli33\JsonToCfdi\StampService\StampException;
use Celli33\JsonToCfdi\StampService\StampServiceInterface;
use Celli33\JsonToCfdi\Values\XmlContent;

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
