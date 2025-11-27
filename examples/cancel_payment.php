<?php

use FaganChalabizada\BirPay\BirPay;
use FaganChalabizada\BirPay\Exception\BirPayException;

require __DIR__ . '/../vendor/autoload.php';


$birpay = new BirPay();

try {
    $cancelPayment = $birpay->cancelOperation('ac5aa81a-bc42-4ab4-8bb5-d061d0e7420f');

    print_r($cancelPayment->getRawData());
} catch (BirPayException $e) {
    echo $e->getMessage();
}
