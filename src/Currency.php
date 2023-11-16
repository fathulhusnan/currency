<?php

namespace Sirius;

class Currency
{
    private string $currency;
    private string $isIn = 'currency';
    private string $symbol = "Rp";
    private string $countryCode = "IDR";
    
    public function __construct(
        private int|float $nominal = 0,
        private int $decimal_digits = 0,
        private string $decimal_separator = ',',
        private string $thousands_separator = '.',
        private string $prefix = '',
        private string $suffix = ''
    )
    {
        if ($decimal_digits > 10) throw new \Exception("Decimal digit cannot be more than 10!");

        $this->convertToCurrency();
        
        $this->prefix($prefix);
        $this->suffix($suffix);
    }

    public function __toString(): string
    {
        return $this->currency;
    }

    // Getter

    public function getOriginal(): string
    {
        return $this->nominal;
    }

    public function getUnit(): string
    {
        return $this->isIn;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function getSuffix(): string
    {
        return $this->suffix;
    }

    // Transformer

    public function of(int|float $nominal): static
    {
        $this->nominal = $nominal;

        return $this;
    }

    public function convertToCurrency(): static
    {
        if ($this->isIn == 'cent') {
            $this->nominal = $this->nominal / 100;
        }

        $this->isIn('currency');
        $this->currency = number_format($this->nominal, $this->decimal_digits, $this->decimal_separator, $this->thousands_separator);
    
        return $this;
    }

    public function convertToCent(): static
    {
        if ($this->isIn == 'currency') {
            $this->nominal = $this->nominal * 100;
            $this->convertToCurrency();
        }
        
        $this->isIn = 'cent';

        return $this;
    }

    public function isIn(string $unit): static
    {
        if (!in_array($unit, ['currency', 'cent'])) throw new \Exception("Unit can only be 'currency' or 'cent'!");

        $this->isIn = $unit;

        return $this;
    }

    public function unit(string $unit): static
    {
        $this->isIn($unit);

        return $this;
    }

    public function decimalDigits(int $digit): static
    {
        if ($digit > 10) throw new \Exception("Decimal digit cannot be more than 10!");

        $this->decimal_digits = $digit;

        $this->currency = number_format($this->nominal, $this->decimal_digits, $this->decimal_separator, $this->thousands_separator);

        return $this;
    }

    public function decimalSeparator(string $separator): static
    {
        $this->decimal_separator = $separator;

        $this->currency = number_format($this->nominal, $this->decimal_digits, $this->decimal_separator, $this->thousands_separator);

        return $this;
    }

    public function thousandsSeparator(string $separator): static
    {
        $this->thousands_separator = $separator;

        $this->currency = number_format($this->nominal, $this->decimal_digits, $this->decimal_separator, $this->thousands_separator);

        return $this;
    }

    public function prefix(string $prefix): static
    {
        $this->currency = $this->convertToCurrency();

        $this->prefix = $prefix;
        $this->currency = $this->prefix . $this->currency;

        return $this;
    }

    public function suffix(string $suffix): static
    {
        $this->currency = $this->convertToCurrency();

        $this->suffix = $suffix;
        $this->currency = $this->currency . $this->suffix;

        return $this;
    }

    // Useful Transformation

    public function symbolPrefix(): static
    {
        $this->prefix($this->symbol);

        return $this;
    }

    public function countryCodePrefix(): static
    {
        
        $this->prefix("{$this->countryCode} ");

        return $this;
    }

    public function symbolSuffix(): static
    {
        $this->suffix($this->symbol);

        return $this;
    }

    public function countryCodeSuffix(): static
    {
        
        $this->suffix(" {$this->countryCode}");

        return $this;
    }
}