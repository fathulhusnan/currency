<?php

use Sirius\Currency;

if (! function_exists('currency')) {
    function currency(
        int|float $nominal = 0,
        int $decimal_digits = 0,
        string $decimal_separator = ',',
        string $thousands_separator = '.',
        string $prefix = '',
        string $suffix = ''
    ): Currency
    {
        return new Currency(
            $nominal,
            $decimal_digits,
            $decimal_separator,
            $thousands_separator,
            $prefix,
            $suffix,
        );
    }
}