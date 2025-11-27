<?php

namespace FaganChalabizada\BirPay\Enums;

enum ConfirmationType: string
{
    case QR = 'QR';
    case MOBILE = 'MOBILE';
    case REDIRECT = 'REDIRECT';
}