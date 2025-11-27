<?php
require __DIR__ . '/../vendor/autoload.php';

use FaganChalabizada\BirPay\BirPay;
use FaganChalabizada\BirPay\Enums\ConfirmationType;
use FaganChalabizada\BirPay\Enums\PaymentMethod;
use FaganChalabizada\BirPay\Exception\BirPayException;

$birpay = new BirPay();

try {

    $paymentId = '';
    $amount = 0.01;

    $createPayment = $birpay->refundPayment($paymentId, $amount);

    $data = $createPayment->getRawData();

    print_r($data);

} catch (BirPayException $e) {
    echo $e->getMessage();
}
