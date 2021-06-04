<?php

namespace ByTIC\Payments\Mobilpay\Message\Soap\Payment;

use ByTIC\Payments\Gateways\Providers\AbstractGateway\Message\Traits\HasModelRequest;

/**
 * Class DoPayTRequest
 * @package ByTIC\Payments\Mobilpay\Message\Soap\Payment
 */
class DoPayTRequest extends \ByTIC\Omnipay\Mobilpay\Message\Soap\Payment\DoPayTRequest
{
    use HasModelRequest;

}