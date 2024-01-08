<?php

declare(strict_types=1);

namespace Paytic\Payments\Mobilpay\Message\Soap\Payment;

use Paytic\Payments\Gateways\Providers\AbstractGateway\Message\Traits\HasModelProcessedResponse;

/**
 * Class DoPayTResponse
 * @package Paytic\Payments\Mobilpay\Message\Soap\Payment
 */
class DoPayTResponse extends \Paytic\Omnipay\Mobilpay\Message\Soap\Payment\DoPayTResponse
{
    use HasModelProcessedResponse;

    protected function canProcessModel(): bool
    {
        return true;
    }
}