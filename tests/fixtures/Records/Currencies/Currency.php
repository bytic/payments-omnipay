<?php

namespace ByTIC\Payments\Tests\Fixtures\Records\Currencies;

use ByTIC\Payments\Models\Currencies\Traits\CurrencyTrait;
use Nip\Records\AbstractModels\Record;

/**
 * Class Currency
 * @package ByTIC\Payments\Tests\Fixtures\Records\Currencies
 */
class Currency extends Record
{
    use CurrencyTrait;
}
