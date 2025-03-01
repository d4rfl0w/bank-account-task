<?php

declare(strict_types=1);

namespace BankingSystem\Domain\Entity;

use BankingSystem\Domain\ValueObject\Currency;

class Payment
{
    private float $amount;
    private Currency $currency;

    public function __construct(float $amount, Currency $currency)
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException("Amount must be positive.");
        }
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }
}
