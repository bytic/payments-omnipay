<?php

namespace ByTIC\Payments\Mobilpay\Message\Traits;

use ByTIC\Payments\Gateways\Providers\AbstractGateway\Message\Traits\HasGatewayRequestTrait;
use ByTIC\Payments\Gateways\Providers\AbstractGateway\Message\Traits\HasModelRequest;
use ByTIC\Payments\Mobilpay\Gateway;

/**
 * Trait CompletePurchaseRequestTrait
 * @package ByTIC\Payments\Mobilpay\Message\Traits
 */
trait CompletePurchaseRequestTrait
{
    use HasGatewayRequestTrait;
    use HasModelRequest;


    /**
     * @return bool|mixed
     * @throws \Exception
     */
    protected function parseNotification()
    {
        if ($this->validateModel()) {
            $model = $this->getModel();
            $this->updateParametersFromPurchase($model);
        }

        return parent::parseNotification();
    }

    /**
     * @param Gateway $modelGateway
     */
    protected function updateParametersFromGateway($modelGateway)
    {
        $this->setSignature($modelGateway->getSignature());
        $this->setCertificate($modelGateway->getCertificate());
        $this->setPrivateKey($modelGateway->getPrivateKey());
    }
}
