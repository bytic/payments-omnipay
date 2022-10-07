<?php

declare(strict_types=1);

$configData = [
    'gateways' => ['Mobilpay']
];

require './vendor/paytic/payments-tests/src/boostrap/bootstrap.php';

translator()->setLocale('ro');