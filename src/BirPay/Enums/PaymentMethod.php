<?php

namespace FaganChalabizada\BirPay\Enums;

enum PaymentMethod: string
{
    case M10 = 'M10';
    case BIRBANK = 'BIRBANK';
    case BANK_CARD = 'BANK_CARD';
}