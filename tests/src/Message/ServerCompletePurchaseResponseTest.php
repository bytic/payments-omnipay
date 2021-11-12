<?php

namespace Paytic\Payments\Mobilpay\Tests\Message;

use Paytic\Payments\Mobilpay\Message\ServerCompletePurchaseRequest;
use Paytic\Payments\Mobilpay\Message\ServerCompletePurchaseResponse;
use Paytic\Payments\Mobilpay\Tests\AbstractTest;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ServerCompletePurchaseResponseTest
 * @package Paytic\Payments\Mobilpay\Tests\Message
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
