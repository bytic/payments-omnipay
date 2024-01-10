<?php

namespace Paytic\Payments\Mobilpay;

use Paytic\Omnipay\Mobilpay\Gateway as AbstractGateway;
use Paytic\Payments\Gateways\Providers\AbstractGateway\Traits\GatewayTrait;
use Paytic\Payments\Gateways\Providers\AbstractGateway\Traits\OverwriteServerCompletePurchaseTrait;
use Paytic\Payments\Mobilpay\FileLoader\HasFileLoader;
use Omnipay\Common\Message\RequestInterface;

/**
 * Class Gateway
 * @package Paytic\Payments\Mobilpay
 * @method \Omnipay\Common\Message\NotificationInterface acceptNotification(array $options = [])
 * @method \Omnipay\Common\Message\RequestInterface fetchTransaction(array $options = [])
 */
class Gateway extends AbstractGateway
{
    use GatewayTrait;
    use OverwriteServerCompletePurchaseTrait;
    use HasFileLoader;

    // ------------ REQUESTS ------------ //
    /**
     * @param array $parameters
     * @return RequestInterface|null
     */
    public function doPayT(array $parameters = []): RequestInterface
    {
        return $this->createRequestWithInternalCheck('Soap\Payment\DoPayTRequest', $parameters);
    }

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

    /**
     * @inheritDoc
     */
    public function setPassword($value)
    {
        $value = html_entity_decode($value);
        return parent::setPassword($value);
    }
}
