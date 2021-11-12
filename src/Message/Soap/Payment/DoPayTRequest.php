<?php

namespace Paytic\Payments\Mobilpay\Message\Soap\Payment;

use ByTIC\Payments\Gateways\Providers\AbstractGateway\Message\Traits\HasModelRequest;

/**
 * Class DoPayTRequest
 * @package Paytic\Payments\Mobilpay\Message\Soap\Payment
 */
class DoPayTRequest extends \Paytic\Omnipay\Mobilpay\Message\Soap\Payment\DoPayTRequest
{
    use HasModelRequest;

}