<?php

declare(strict_types=1);

namespace Celli33\JsonToCfdi\Actions\BuildCfdiFromJson;

use Celli33\JsonToCfdi\Actions\ConvertJsonToXml\ConvertJsonToXmlAction;
use Celli33\JsonToCfdi\Actions\SignXml\SignXmlAction;
use Celli33\JsonToCfdi\Actions\StampCfdi\StampCfdiAction;
use Celli33\JsonToCfdi\JsonToXmlConverter\JsonToXmlConvertException;
use Celli33\JsonToCfdi\PreCfdiSigner\UnableToSignXmlException;
use Celli33\JsonToCfdi\StampService\ServiceException;
use Celli33\JsonToCfdi\StampService\StampException;
use Celli33\JsonToCfdi\Values\Csd;
use Celli33\JsonToCfdi\Values\JsonContent;

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
