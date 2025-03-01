<?php

declare(strict_types=1);

namespace BankingSystem\Domain\ValueObject;

use InvalidArgumentException;

class Currency
{
    private const ALLOWED_CURRENCIES = ['USD', 'EUR', 'GBP', 'PLN'];

    public function __construct(
        private string $code
    ) {
        if (! in_array($this->code, self::ALLOWED_CURRENCIES)) {
            throw new InvalidArgumentException("Invalid currency: {$this->code}");
        }
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function equals(self $currency): bool
    {
        return $this->code === $currency->getCode();
    }
}
