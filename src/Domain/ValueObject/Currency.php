<?php

declare(strict_types=1);

namespace BankingSystem\Domain\ValueObject;

use InvalidArgumentException;

class Currency
{
    private string $code;

    private const ALLOWED_CURRENCIES = ['USD', 'EUR', 'GBP', 'PLN'];

    public function __construct(string $code)
    {
        if (!in_array($code, self::ALLOWED_CURRENCIES)) {
            throw new InvalidArgumentException("Invalid currency: $code");
        }
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function equals(Currency $currency): bool
    {
        return $this->code === $currency->getCode();
    }
}
