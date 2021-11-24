<?php

declare(strict_types=1);

namespace PhpCfdi\JsonToCfdiBridge\Actions\BuildPreCfdiFromJson;

use PhpCfdi\JsonToCfdiBridge\Actions\ConvertJsonToXml\ConvertJsonToXmlAction;
use PhpCfdi\JsonToCfdiBridge\Actions\SignXml\SignXmlAction;
use PhpCfdi\JsonToCfdiBridge\JsonToXmlConverter\JsonToXmlConvertException;
use PhpCfdi\JsonToCfdiBridge\PreCfdiSigner\UnableToSignXmlException;
use PhpCfdi\JsonToCfdiBridge\Values\Csd;
use PhpCfdi\JsonToCfdiBridge\Values\JsonContent;

class BuildPreCfdiFromJsonAction
{
    public function __construct(
        private ConvertJsonToXmlAction $convertJsonToXmlAction,
        private SignXmlAction $signXmlAction,
    ) {
    }

    public function getSignXmlAction(): SignXmlAction
    {
        return $this->signXmlAction;
    }

    /**
     * @throws UnableToSignXmlException
     * @throws JsonToXmlConvertException
     */
    public function execute(JsonContent $json, Csd $csd): CreatePreCfdiFromJsonResult
    {
        $convertResult = $this->convertJsonToXmlAction->execute($json);
        $preCfdiResult = $this->signXmlAction->execute($convertResult->getXml(), $csd);
        return new CreatePreCfdiFromJsonResult(
            $json,
            $convertResult->getXml(),
            $preCfdiResult->getPreCfdi(),
        );
    }
}
