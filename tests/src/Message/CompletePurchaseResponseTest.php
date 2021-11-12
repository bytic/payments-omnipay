<?php

namespace Paytic\Payments\Mobilpay\Tests\Message;

use Paytic\Payments\Mobilpay\Message\CompletePurchaseRequest;
use Paytic\Payments\Mobilpay\Message\CompletePurchaseResponse;
use Paytic\Payments\Mobilpay\Tests\AbstractTest;
use ByTIC\Payments\Tests\Fixtures\Records\Purchases\PurchasableRecord;
use ByTIC\Payments\Tests\Fixtures\Records\Purchases\PurchasableRecordManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CompletePurchaseResponseTest
 * @package Paytic\Payments\Mobilpay\Tests\Message
 */
class CompletePurchaseResponseTest extends AbstractTest
{

    public function testResponseForValidTransaction()
    {
        $httpRequest = $this->generateRequestFromFixtures(
            TEST_FIXTURE_PATH . '/requests/completePurchase/basicParams.php'
        );

        $model = \Mockery::mock(PurchasableRecord::class)->makePartial();
        $model->shouldReceive('getPaymentGateway')->andReturn(null);

        $modelManager = \Mockery::mock(PurchasableRecordManager::class)->makePartial();
        $modelManager->shouldReceive('findOne')->andReturn($model);

        $request = new CompletePurchaseRequest($this->client, $httpRequest);
        $request->setModelManager($modelManager);

        $response = $request->send();
        self::assertInstanceOf(CompletePurchaseResponse::class, $response);
        self::assertSame(false, $response->isCancelled());
        self::assertSame(true, $response->isPending());
        self::assertSame('pending', $response->getModelResponseStatus());

//        $content = $response->getViewContent();
//
//        self::assertStringContainsString('++++', $content);
    }

    public function testHasConfirmHtmlTraitTrait()
    {
        $response = $this->getNewResponse();

        static::assertTrue(method_exists($response, 'getViewFile'));
        static::assertTrue(method_exists($response, 'getView'));
    }

    protected function getNewResponse()
    {
        $request = new CompletePurchaseRequest($this->client, new Request());

        return new CompletePurchaseResponse($request, []);
    }
}
