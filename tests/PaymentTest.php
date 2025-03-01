<?php

declare(strict_types=1);

use BankingSystem\Domain\Entity\Payment;
use BankingSystem\Domain\ValueObject\Currency;
use PHPUnit\Framework\TestCase;

class PaymentTest extends TestCase
{
    public function testCreateValidPayment(): void
    {
        $payment = new Payment(100.0, new Currency('USD'));

        $this->assertEquals(100.0, $payment->getAmount());
        $this->assertEquals('USD', $payment->getCurrency()->getCode());
    }

    public function testCreatePaymentWithZeroAmountThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Payment(0.0, new Currency('USD'));
    }

    public function testCreatePaymentWithNegativeAmountThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Payment(-10.0, new Currency('USD'));
    }

    public function testCreatePaymentWithInvalidCurrencyThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Currency('ABC');
    }

    public function testCurrenciesAreEqual(): void
    {
        $currency1 = new Currency('USD');
        $currency2 = new Currency('USD');

        $this->assertTrue($currency1->equals($currency2));
    }

    public function testCurrenciesAreNotEqual(): void
    {
        $currency1 = new Currency('USD');
        $currency2 = new Currency('EUR');

        $this->assertFalse($currency1->equals($currency2));
    }
}
