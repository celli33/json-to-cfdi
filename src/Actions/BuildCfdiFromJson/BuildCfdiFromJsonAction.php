<?php

declare(strict_types=1);

namespace PhpCfdi\JsonToCfdiBridge\Actions\BuildCfdiFromJson;

use PhpCfdi\JsonToCfdiBridge\Actions\ConvertJsonToXml\ConvertJsonToXmlAction;
use PhpCfdi\JsonToCfdiBridge\Actions\SignXml\SignXmlAction;
use PhpCfdi\JsonToCfdiBridge\Actions\StampCfdi\StampCfdiAction;
use PhpCfdi\JsonToCfdiBridge\JsonToXmlConverter\JsonToXmlConvertException;
use PhpCfdi\JsonToCfdiBridge\PreCfdiSigner\UnableToSignXmlException;
use PhpCfdi\JsonToCfdiBridge\StampService\ServiceException;
use PhpCfdi\JsonToCfdiBridge\StampService\StampException;
use PhpCfdi\JsonToCfdiBridge\Values\Csd;
use PhpCfdi\JsonToCfdiBridge\Values\JsonContent;

class BuildCfdiFromJsonAction
{
    public function __construct(
        private ConvertJsonToXmlAction $convertJsonToXmlAction,
        private SignXmlAction $signXmlAction,
        private StampCfdiAction $stampCfdiAction,
    ) {
    }

    public function getSignXmlAction(): SignXmlAction
    {
        return $this->signXmlAction;
    }

    /**
     * @throws UnableToSignXmlException
     * @throws JsonToXmlConvertException
     * @throws StampException
     * @throws ServiceException
     */
    public function execute(JsonContent $json, Csd $csd): CreateCfdiFromJsonResult
    {
        $convertResult = $this->convertJsonToXmlAction->execute($json);
        $preCfdiResult = $this->signXmlAction->execute($convertResult->getXml(), $csd);
        $cfdiResult = $this->stampCfdiAction->execute($preCfdiResult->getPreCfdi()->getXml());
        return new CreateCfdiFromJsonResult(
            $json,
            $convertResult->getXml(),
            $preCfdiResult->getPreCfdi(),
            $cfdiResult->getCfdi(),
        );
    }
}
