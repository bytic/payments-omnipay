<?php

namespace ByTIC\Payments\Tests\Fixtures\Records\Purchases;

use ByTIC\Payments\Models\Methods\Traits\RecordTrait;
use ByTIC\Payments\Models\Purchase\Traits\IsPurchasableModelTrait;
use Nip\Records\AbstractModels\Record;

/**
 * Class PurchasableRecord
 * @package ByTIC\Payments\Tests\Fixtures\Records\Purchases
 */
class PurchasableRecord extends Record
{
    use IsPurchasableModelTrait;

    public $id = 37250;

    /**
     * @return int
     */
    public function getPurchaseAmount()
    {
        return 10.00;
    }

    /**
     * @return string
     */
    public function getConfirmURL()
    {
        return 'http://hospice.galantom.ro/donations/confirm?id='.$this->id;
    }

    /**
     * @return string
     */
    public function getIpnURL()
    {
        return 'http://ipn.ro';
    }

    /**
     * @return RecordTrait
     */
    public function getPaymentMethod()
    {
    }

    public function getName()
    {
        // TODO: Implement getName() method.
    }
}
