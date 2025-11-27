<?php

use FaganChalabizada\BirPay\BirPay;
use FaganChalabizada\BirPay\Exception\BirPayException;

require __DIR__ . '/../vendor/autoload.php';


$birpay = new BirPay();

try {
    $request = $birpay->catchWebhook();

    echo "Event: " . $request->getEvent() . "\n";
    echo "Transaction id: " . $request->getTransactionId() . "\n";
    echo "Payment status: " . $request->getPaymentStatus() . "\n";
    echo "Type: " . $request->getType() . "\n";
    echo "Raw data: " . print_r($request->getRawData(), 1) . "\n";

} catch (BirPayException $e) {
    echo $e->getMessage();
}
