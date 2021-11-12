<?php

namespace Paytic\Payments\Mobilpay\Message;

use Paytic\Omnipay\Mobilpay\Message\ServerCompletePurchaseRequest as AbstractServerCompletePurchaseRequest;

/**
 * Class ServerCompletePurchaseRequest
 * @package Paytic\Payments\Mobilpay\Message
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
