<?php

namespace ByTIC\Payments\Tests\Fixtures\Records;

use ByTIC\Payments\Models\BillingRecord\Traits\RecordTrait as BillingRecordTrait;
use ByTIC\Common\Records\Traits\HasSerializedOptions\RecordTrait as HasSerializedOptions;
use Nip\Records\AbstractModels\Record;

/**
 * Class PurchasableRecord
 */
class BillingRecord extends Record
{
    use HasSerializedOptions;
    use BillingRecordTrait;

    public $phone = '99';

    /**
     * @return string
     */
    public function getFirstName()
    {
        return 'John';
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return 'Doe';
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return 'john@doe.com';
    }

    public function getRegistry()
    {
    }
}
