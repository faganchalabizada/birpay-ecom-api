<?php
require __DIR__ . '/../vendor/autoload.php';

use FaganChalabizada\BirPay\BirPay;
use FaganChalabizada\BirPay\Enums\ConfirmationType;
use FaganChalabizada\BirPay\Enums\PaymentMethod;
use FaganChalabizada\BirPay\Exception\BirPayException;

$birpay = new BirPay("qq");

try {

    $createPayment = $birpay->createPayment("test2", "test payment", PaymentMethod::M10, ConfirmationType::QR, "aa.com", 1);

    $data = $createPayment->getRawData();

    print_r($data);

} catch (BirPayException $e) {
    echo $e->getMessage();
}
