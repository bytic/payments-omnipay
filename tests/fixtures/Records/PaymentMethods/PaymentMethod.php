<?php

namespace ByTIC\Payments\Mobilpay\Tests\Fixtures\Records\PaymentMethods;

use ByTIC\Payments\Models\Methods\Traits\RecordTrait as PaymentMethodTrait;
use Nip\Records\Record;

/**
 * Class PaymentMethod
 * @package ByTIC\Payments\Tests\Fixtures\Records\PaymentMethods
 */
class PaymentMethod extends Record
{
    use PaymentMethodTrait;

    protected $managerName = PaymentMethods::class;

    public function getRegistry()
    {
    }

//    public function inflectManagerName()
//    {
//        return PaymentMethods::class;
//    }

    /**
     * @return string
     */
    public function getFilesDirectory()
    {
        return TEST_FIXTURE_PATH . '/files/';
    }

    /**
     * @return string
     */
    protected function inflectManagerName()
    {
        return PaymentMethods::class;
    }

    /**
     * @return int
     */
    public function getPurchasesCount()
    {
        return 2;
    }
}
