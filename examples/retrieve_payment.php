<?php

use FaganChalabizada\BirPay\BirPay;
use FaganChalabizada\BirPay\Exception\BirPayException;

require __DIR__ . '/../vendor/autoload.php';

$birpay = new BirPay();
//$birpay->setPrintResponse(true);
try {
    $check_payment = $birpay->retrievePayment("ac5aa81a-bc42-4ab4-8bb5-d061d0e7420f");

    echo "\nGet status: " . $check_payment->getPaymentStatus() . "\n";
    echo "Get getTransactionId: " . $check_payment->getTransactionId() . "\n";
    echo "Get getAmount: " . $check_payment->getAmount() . "\n";
    echo "Get getCurrency: " . $check_payment->getCurrency() . "\n";
    echo "Get getHttpCode: " . $check_payment->getHttpCode() . "\n";
    echo "Get getMerchantDetails: " . print_r($check_payment->getMerchantDetails(), 1) . "\n";
    echo "Get getCancelationParty: " . $check_payment->getCancelationParty() . "\n";
    echo "Get getCancelationReason: " . $check_payment->getCancelationReason() . "\n";
    echo "Get getConfirmationType: " . $check_payment->getConfirmationType() . "\n";
    echo "Get getConfirmationUrl: " . $check_payment->getConfirmationUrl() . "\n";
    echo "Get getConfirmationData: " . $check_payment->getConfirmationData() . "\n";
    echo "Get getDescription: " . $check_payment->getDescription() . "\n";
    echo "Get getExpirationDate: " . $check_payment->getExpirationDate() . "\n";
    echo "Get getReturnUrl: " . $check_payment->getReturnUrl() . "\n";
    echo "Get getMessage: " . $check_payment->getErrorMessage() . "\n";

} catch (BirPayException $e) {
    echo $e->getMessage();
}
