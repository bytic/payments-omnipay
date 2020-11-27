<?php

namespace ByTIC\Payments\Mobilpay\Tests\Message;

use ByTIC\Payments\Gateways\Providers\Mobilpay\Message\ServerCompletePurchaseRequest;
use ByTIC\Payments\Gateways\Providers\Mobilpay\Message\ServerCompletePurchaseResponse;
use ByTIC\Payments\Mobilpay\Tests\AbstractTest;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ServerCompletePurchaseResponseTest
 * @package ByTIC\Payments\Mobilpay\Tests\Message
 */
class ServerCompletePurchaseResponseTest extends AbstractTest
{
    public function testHasSendMethod()
    {
        $response = $this->getNewResponse();

        static::assertTrue(method_exists($response, 'send'));
    }

    /**
     * @return ServerCompletePurchaseResponse
     */
    protected function getNewResponse()
    {
        $request = new ServerCompletePurchaseRequest($this->client, new Request());

        return new ServerCompletePurchaseResponse($request, []);
    }
}
