<?php

declare(strict_types=1);

namespace Paytic\Payments\Mobilpay\Tests\Message\Soap\Payment;

use Paytic\Payments\Mobilpay\Message\Soap\Payment\DoPayTRequest;
use Paytic\Payments\Mobilpay\Message\Soap\Payment\DoPayTResponse;
use Paytic\Payments\Mobilpay\Tests\AbstractTest;

/**
 *
 */
class DoPayTRequestTest extends AbstractTest
{
    public function test()
    {
        $request = \Mockery::mock(DoPayTRequest::class)
            ->makePartial();
        $request->shouldAllowMockingProtectedMethods();
        $request->shouldReceive('getData')->once()->andReturn([]);
        $request->shouldReceive('buildSoapClient')->once()->andReturn([]);
        $request->shouldReceive('runTransaction')->once()->andReturn([]);
        $response = $request->send();
        self::assertInstanceOf(DoPayTResponse::class, $response);
    }
}
