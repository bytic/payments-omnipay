<?php

namespace Paytic\Payments\Mobilpay\Message\Soap\Payment;

use ByTIC\Payments\Gateways\Providers\AbstractGateway\Message\Traits\HasModelProcessedResponse;

/**
 * Class DoPayTResponse
 * @package Paytic\Payments\Mobilpay\Message\Soap\Payment
 */
class DoPayTResponse extends \Paytic\Omnipay\Mobilpay\Message\Soap\Payment\DoPayTResponse
{
    use HasModelProcessedResponse;
}