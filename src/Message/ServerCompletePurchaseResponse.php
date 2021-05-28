<?php

namespace ByTIC\Payments\Mobilpay\Message;

use ByTIC\Omnipay\Mobilpay\Message\ServerCompletePurchaseResponse as AbstractServerCompletePurchaseResponse;
use ByTIC\Payments\Gateways\Providers\AbstractGateway\Message\Traits\CompletePurchaseResponseTrait;

/**
 * Class ServerCompletePurchaseResponse
 * @package ByTIC\Payments\Mobilpay\Message
 */
class ServerCompletePurchaseResponse extends AbstractServerCompletePurchaseResponse
{
    use CompletePurchaseResponseTrait;

    /**
     * @return []
     */
    public function getSessionDebug(): array
    {
        $notification = $this->getMobilpayNotify();
        return [
            'notify' => $notification
        ];
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     * @return bool
     */
    protected function canProcessModel()
    {
        return true;
    }
}
