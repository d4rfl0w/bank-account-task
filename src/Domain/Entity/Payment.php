<?php

declare(strict_types=1);

namespace BankingSystem\Domain\Entity;

use BankingSystem\Domain\ValueObject\Currency;
use InvalidArgumentException;

final class Payment
{
    public function __construct(
        private float $amount,
        private Currency $currency
    ) {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Amount must be positive.');
        }
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
