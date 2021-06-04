<?php

namespace ByTIC\Payments\Mobilpay\Tests;

use ByTIC\Common\Tests\Fixtures\Unit\Payments\PaymentMethod;
use ByTIC\Omnipay\Mobilpay\Message\CompletePurchaseResponse;
use ByTIC\Omnipay\Mobilpay\Message\PurchaseRequest;
use ByTIC\Omnipay\Mobilpay\Message\PurchaseResponse;
use ByTIC\Omnipay\Mobilpay\Message\ServerCompletePurchaseResponse;
use ByTIC\Omnipay\Mobilpay\Models\Request\Card;
use ByTIC\Payments\Mobilpay\Gateway;
use ByTIC\Payments\Mobilpay\Message\Soap\Payment\DoPayTRequest;
use ByTIC\Payments\Mobilpay\Tests\Fixtures\MobilpayData;
use Http\Discovery\Psr17FactoryDiscovery;
use Omnipay\Common\Message\AbstractRequest;

/**
 * Class GatewayTest
 * @package ByTIC\Payments\Mobilpay\Tests
 */
class GatewayTest extends \ByTIC\Payments\Tests\Gateways\GatewayTest
{
    /**
     * @var Gateway
     */
    protected $gateway;

    public function testPurchaseResponse()
    {
        $this->purchase->id = rand(111111111, 999999999);

        /** @var PurchaseRequest $request */
        $request = $this->gateway->purchaseFromModel($this->purchase);
        self::assertSame(false, $request->getTestMode());

        /** @var PurchaseResponse $response */
        $response = $request->send();
        self::assertInstanceOf(PurchaseResponse::class, $response);

        $data = $response->getRedirectData();
        self::assertCount(2, $data);
        self::assertSame('https://secure.mobilpay.ro', $response->getRedirectUrl());

        $gatewayResponse = $this->client->request(
            'POST',
            $response->getRedirectUrl(),
            ['Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8'],
            Psr17FactoryDiscovery::findStreamFactory()->createStream(http_build_query($data, '', '&'))
        );

        self::assertSame(200, $gatewayResponse->getStatusCode());

        //Validate first Response
        $body = $gatewayResponse->getBody()->__toString();
        self::assertRegExp('/ID Tranzactie/', $body);
        self::assertRegExp('/Descriere plata/', $body);
        self::assertRegExp('/Site comerciant/', $body);
    }

    public function testPurchaseResponseSandbox()
    {
//        Debug::debug($this->gateway->getParameters());
        $this->gateway->setTestMode(true);
        /** @var PurchaseRequest $request */
        $request = $this->gateway->purchaseFromModel($this->purchase);
        self::assertSame(true, $request->getTestMode());

        $response = $request->send();
        self::assertInstanceOf(PurchaseResponse::class, $response);

        $data = $response->getRedirectData();

        self::assertCount(2, $data);
        self::assertSame('http://sandboxsecure.mobilpay.ro', $response->getRedirectUrl());

        $gatewayResponse = $this->client->request(
            'POST',
            $response->getRedirectUrl(),
            ['Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8'],
            Psr17FactoryDiscovery::findStreamFactory()->createStream(http_build_query($data, '', '&'))
        );
        self::assertSame(200, $gatewayResponse->getStatusCode());

        //Validate first Response
        $body = $gatewayResponse->getBody()->__toString();
        self::assertRegExp('/ID Tranzactie/', $body);
        self::assertRegExp('/Descriere plata/', $body);
        self::assertRegExp('/Site comerciant/', $body);
    }

    public function test_purchaseWithToken()
    {
        $request = $this->gateway->purchaseWithToken([]);
        self::assertInstanceOf(DoPayTRequest::class, $request);

    }

    public function testCompletePurchaseResponse()
    {
        $httpRequest = MobilpayData::getCompletePurchaseRequest();

        /** @var CompletePurchaseResponse $response */
        $response = $this->gatewayManager->detectItemFromHttpRequest(
            $this->purchaseManager,
            'completePurchase',
            $httpRequest
        );

        self::assertInstanceOf(CompletePurchaseResponse::class, $response);

        $model = $response->getModel();
        self::assertSame($response->isSuccessful(), $model->status == 'active');
        self::assertEquals($httpRequest->query->get('id'), $model->id);
    }

    public function test_ServerCompletePurchaseConfirmedResponse()
    {
        $httpRequest = MobilpayData::getServerCompletePurchaseRequest();
        $response = $this->createServerCompletePurchaseResponse($httpRequest);

        self::assertTrue($response->isSuccessful());

        $content = $response->getContent();
        $validContent = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
        $validContent .= '<crc>1e59360874ae14eb39c7a038b205bf0d</crc>';
        self::assertSame($validContent, $content);
    }

    /**
     * @param $request
     * @return ServerCompletePurchaseResponse
     */
    protected function createServerCompletePurchaseResponse($request)
    {
        /** @var ServerCompletePurchaseResponse $response */
        $response = $this->gatewayManager->detectItemFromHttpRequest(
            $this->purchaseManager,
            'serverCompletePurchase',
            $request
        );

        self::assertInstanceOf(ServerCompletePurchaseResponse::class, $response);
        self::assertInstanceOf(Card::class, $response->getMobilpayRequest());

        return $response;
    }

    public function test_ServerCompletePurchaseInsufficientFondsResponse()
    {
        $httpRequest = MobilpayData::getServerCompletePurchaseRequestInsufficientFonds();
        $response = $this->createServerCompletePurchaseResponse($httpRequest);

        self::assertSame('20', $response->getCode());
        self::assertFalse($response->isSuccessful());
        self::assertFalse($response->isPending());
        self::assertFalse($response->isCancelled());
        self::assertSame('error', $response->getModelResponseStatus());
        self::assertSame('Fonduri insuficiente.', $response->getMessage());

        $content = $response->getContent();
        $validContent = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
        $validContent .= '<crc error_type="20" error_code="20">Fonduri insuficiente.</crc>';
        self::assertSame($validContent, $content);
    }

    public function testServerCompletePurchaseGenericError35Response()
    {
        $httpRequest = MobilpayData::getServerCompletePurchaseRequestGenericError35();
        $response = $this->createServerCompletePurchaseResponse($httpRequest);

        self::assertSame('35', $response->getCode());
        self::assertFalse($response->isSuccessful());
        self::assertFalse($response->isPending());
        self::assertFalse($response->isCancelled());
        self::assertSame('error', $response->getModelResponseStatus());
        self::assertSame(' ', $response->getMessage());

        $content = $response->getContent();
        $validContent = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
        $validContent .= '<crc error_type="35" error_code="35"> </crc>';
        self::assertSame($validContent, $content);
    }

    public function testServerCompletePurchaseDeclined3DSecureResponse()
    {
        $httpRequest = MobilpayData::getServerCompletePurchaseRequestDeclined3DSecure();
        $response = $this->createServerCompletePurchaseResponse($httpRequest);

        self::assertSame('39', $response->getCode());
        self::assertFalse($response->isSuccessful());
        self::assertFalse($response->isPending());
        self::assertFalse($response->isCancelled());
        self::assertSame('error', $response->getModelResponseStatus());
        self::assertSame('gwDeclined3DSecure', $response->getMessage());

        $content = $response->getContent();
        $validContent = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
        $validContent .= '<crc error_type="39" error_code="39">gwDeclined3DSecure</crc>';
        self::assertSame($validContent, $content);
    }

    protected function setUp(): void
    {
        parent::setUp();

        /** @var PaymentMethod $paymentMethod */
        $paymentMethod = $this->purchase->getPaymentMethod();
        $paymentMethod->options = trim(MobilpayData::getMethodOptions());

        $this->purchase->created = date('Y-m-d H:i:s');

        MobilpayData::buildCertificates();
        $this->gateway = $paymentMethod->getType()->getGateway();
        $this->gateway->setPaymentMethod($paymentMethod);
    }

    /**
     * @param $purchase
     * @return \Mockery\Mock
     */
    protected function generatePurchaseManagerMock($purchase)
    {
        $manager = parent::generatePurchaseManagerMock($purchase);

        $purchase->id = 39188;
        $manager->shouldReceive('findOne')->withAnyArgs()->andReturn($purchase);

        return $manager;
    }
}
