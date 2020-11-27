<?php

namespace ByTIC\Payments\Mobilpay\Message;

use ByTIC\Omnipay\Mobilpay\Message\CompletePurchaseRequest as AbstractCompletePurchaseRequest;

/**
 * Class PurchaseResponse
 * @package ByTIC\Payments\Mobilpay\Message
 *
 * @method CompletePurchaseResponse send
 */
class CompletePurchaseRequest extends AbstractCompletePurchaseRequest
{
    use Traits\CompletePurchaseRequestTrait;

    /**
     * @inheritdoc
     */
    public function getData()
    {
        $return = parent::getData();
        // Add model only if has data
        if (count($return) && $this->validateModel()) {
            $return['model'] = $this->getModel();
        }

        return $return;
    }

    /**
     * @inheritDoc
     */
    public function getModelIdFromRequest()
    {
        $modelId = $this->httpRequest->query->get('orderId');

        return $modelId;
    }
}
