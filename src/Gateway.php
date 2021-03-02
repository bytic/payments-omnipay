<?php

namespace ByTIC\Payments\Mobilpay;

use ByTIC\Omnipay\Mobilpay\Gateway as AbstractGateway;
use ByTIC\Payments\Gateways\Providers\AbstractGateway\Traits\GatewayTrait;
use ByTIC\Payments\Gateways\Providers\AbstractGateway\Traits\OverwriteServerCompletePurchaseTrait;
use ByTIC\Payments\Mobilpay\FileLoader\HasFileLoader;

/**
 * Class Gateway
 * @package ByTIC\Payments\Mobilpay
 * @method \Omnipay\Common\Message\NotificationInterface acceptNotification(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface fetchTransaction(array $options = [])
 */
class Gateway extends AbstractGateway
{
    use GatewayTrait;
    use OverwriteServerCompletePurchaseTrait;
    use HasFileLoader;

    /**
     * @inheritDoc
     */
    public function setSandbox($value)
    {
        return $this->setTestMode($value == 'yes');
    }

    /**
     * @inheritDoc
     */
    public function getSandbox()
    {
        return $this->getTestMode() === true ? 'yes' : 'no';
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        if (strlen($this->getCertificate()) >= 5 && strlen($this->getPrivateKey()) > 10) {
            return true;
        }

        return false;
    }

}
