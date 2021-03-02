<?php

namespace ByTIC\Payments\Mobilpay\Message;

use ByTIC\Omnipay\Mobilpay\Message\ServerCompletePurchaseRequest as AbstractServerCompletePurchaseRequest;

/**
 * Class ServerCompletePurchaseRequest
 * @package ByTIC\Payments\Mobilpay\Message
 */
class ServerCompletePurchaseRequest extends AbstractServerCompletePurchaseRequest
{
    use Traits\CompletePurchaseRequestTrait;

    /**
     * @inheritdoc
     */
    public function getData()
    {
        $return = parent::getData();
        // Add model only if has data
        if (count($return)) {
            $return['model'] = $this->getModel();
        }

        return $return;
    }
}
