<?php

declare(strict_types=1);

namespace Paytic\Payments\Mobilpay\Message\Soap\Payment;

use Paytic\Payments\Gateways\Providers\AbstractGateway\Message\Traits\HasModelRequest;

/**
 * Class DoPayTRequest
 * @package Paytic\Payments\Mobilpay\Message\Soap\Payment
 */
class DoPayTRequest extends \Paytic\Omnipay\Mobilpay\Message\Soap\Payment\DoPayTRequest
{
    use HasModelRequest;

    /**
     * @inheritDoc
     */
    protected function getResponseClass(): string
    {
        return DoPayTResponse::class;
    }
}