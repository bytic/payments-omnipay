<?php

namespace ByTIC\Payments\Tests\Fixtures\Records\PaymentMethods;

use ByTIC\Payments\Models\Methods\Traits\RecordsTrait;
use Nip\Records\RecordManager;
use Nip\Utility\Traits\SingletonTrait;

/**
 * Class PaymentMethods
 * @package ByTIC\Payments\Tests\Fixtures\Records\PaymentMethods
 */
class PaymentMethods extends RecordManager
{
    use SingletonTrait;
    use RecordsTrait;
}
