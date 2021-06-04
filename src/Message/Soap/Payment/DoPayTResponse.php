<?php

namespace ByTIC\Payments\Mobilpay\Message\Soap\Payment;

use ByTIC\Payments\Gateways\Providers\AbstractGateway\Message\Traits\HasModelProcessedResponse;

/**
 * Class DoPayTResponse
 * @package ByTIC\Payments\Mobilpay\Message\Soap\Payment
 */
class DoPayTResponse extends \ByTIC\Omnipay\Mobilpay\Message\Soap\Payment\DoPayTResponse
{
    use HasModelProcessedResponse;
}