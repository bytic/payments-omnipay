<?php

namespace Paytic\Payments\Mobilpay\Tests\FileLoader;

use Paytic\Payments\Mobilpay\Gateway;
use Paytic\Payments\Mobilpay\Tests\AbstractTest;
use Paytic\Payments\Mobilpay\Tests\Fixtures\MobilpayData;
use Paytic\Payments\Mobilpay\Tests\Fixtures\Records\PaymentMethods\PaymentMethod;
use Paytic\Payments\Mobilpay\Tests\Fixtures\Records\PaymentMethods\PaymentMethods;
use ByTIC\Payments\Models\Methods\Types\CreditCards;
use Nip\Records\Locator\ModelLocator;

/**
 * Class HasFileLoaderTest
 * @package Paytic\Payments\Mobilpay\Tests\FileLoader
 */
class HasFileLoaderTest extends AbstractTest
{
    public function testSaveToModelOptions()
    {
        /** @var PaymentMethod $paymentMethod */
        $paymentMethods = \Mockery::mock(PaymentMethods::class)->makePartial();
        $paymentMethods->shouldReceive('save');

        ModelLocator::set(PaymentMethods::class, $paymentMethods);

        $paymentMethod = new PaymentMethod();

        $paymentMethod->type = 'credit-cards';
        $options = unserialize(MobilpayData::getMethodOptions());
        $options['mobilpay']['file'] = 'public.cer';
        $options['mobilpay']['private-key'] = 'private.key';
        $paymentMethod->options = serialize($options);

        $type = new CreditCards();
        $type->setItem($paymentMethod);
        $paymentMethod->setType($type);

        $directoryPath = $paymentMethod->getFilesDirectory();
        MobilpayData::buildCertificates();
        self::assertDirectoryExists($directoryPath);

        /** @var Gateway $gateway */
        $gateway = $paymentMethod->getType()->getGateway();

        self::assertGreaterThan(5, strlen($gateway->getCertificate()));
        self::assertGreaterThan(5, strlen($gateway->getPrivateKey()));

        $options = $paymentMethod->getPaymentGatewayOptions();

        self::assertArrayNotHasKey('file', $options);
        self::assertArrayNotHasKey('private-key', $options);

        self::assertGreaterThan(50, strlen($options['certificate']));
        self::assertGreaterThan(50, strlen($options['privateKey']));
        self::assertDirectoryNotExists($directoryPath);
    }
}
