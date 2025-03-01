<?php

declare(strict_types=1);

use BankingSystem\Domain\Entity\BankAccount;
use BankingSystem\Domain\Entity\Payment;
use BankingSystem\Domain\Exception\InsufficientBalanceException;
use BankingSystem\Domain\Exception\TransactionLimitExceededException;
use BankingSystem\Domain\ValueObject\Currency;
use PHPUnit\Framework\TestCase;

class BankAccountTest extends TestCase
{
    public function testCreateAccountWithPositiveBalance(): void
    {
        $account = new BankAccount(new Currency('USD'), 100.0);
        $this->assertEquals(100.0, $account->getBalance());
    }

    public function testCreateAccountWithZeroBalance(): void
    {
        $account = new BankAccount(new Currency('USD'), 0.0);
        $this->assertEquals(0.0, $account->getBalance());
    }

    public function testCreateAccountWithNegativeBalanceThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new BankAccount(new Currency('USD'), -50.0);
    }

    public function testCreditAccountWithSameCurrency(): void
    {
        $account = new BankAccount(new Currency('USD'), 100.0);
        $payment = new Payment(50.0, new Currency('USD'));

        $account->credit($payment);

        $this->assertEquals(150.0, $account->getBalance());
    }

    public function testCreditAccountWithDifferentCurrencyThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $account = new BankAccount(new Currency('USD'), 100.0);
        $payment = new Payment(50.0, new Currency('EUR'));

        $account->credit($payment);
    }

    public function testDebitAccountWithSufficientFunds(): void
    {
        $account = new BankAccount(new Currency('USD'), 100.0);
        $payment = new Payment(50.0, new Currency('USD'));

        $account->debit($payment);

        $expectedBalance = 100.0 - (50.0 + (50.0 * 0.005));
        $this->assertEquals($expectedBalance, $account->getBalance());
    }

    public function testDebitAccountWithInsufficientFundsThrowsException(): void
    {
        $this->expectException(InsufficientBalanceException::class);

        $account = new BankAccount(new Currency('USD'), 20.0);
        $payment = new Payment(30.0, new Currency('USD'));

        $account->debit($payment);
    }

    public function testDebitAccountWithDifferentCurrencyThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $account = new BankAccount(new Currency('USD'), 100.0);
        $payment = new Payment(50.0, new Currency('EUR'));

        $account->debit($payment);
    }

    public function testDebitThreeTransactionsAllowed(): void
    {
        $account = new BankAccount(new Currency('USD'), 500.0);

        for ($i = 0; $i < 3; $i++) {
            $payment = new Payment(50.0, new Currency('USD'));
            $account->debit($payment);
        }

        $this->assertEquals(500.0 - (3 * 50.25), $account->getBalance());
    }

    public function testDebitFourthTransactionThrowsException(): void
    {
        $this->expectException(TransactionLimitExceededException::class);

        $account = new BankAccount(new Currency('USD'), 500.0);

        for ($i = 0; $i < 4; $i++) {
            $payment = new Payment(50.0, new Currency('USD'));
            $account->debit($payment);
        }
    }

    public function testTransactionHistoryRecordsCreditAndDebit(): void
    {
        $account = new BankAccount(new Currency('USD'), 100.0);

        $creditPayment = new Payment(50.0, new Currency('USD'));
        $account->credit($creditPayment);

        $debitPayment = new Payment(30.0, new Currency('USD'));
        $account->debit($debitPayment);

        $history = $account->getTransactionHistory();

        $this->assertCount(2, $history);
        $this->assertEquals('credit', $history[0]['type']);
        $this->assertEquals(50.0, $history[0]['amount']);
        $this->assertEquals('debit', $history[1]['type']);
        $this->assertEquals(30.15, $history[1]['amount']);
    }

    public function testInvalidPaymentAmountThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Payment(-10.0, new Currency('USD'));
    }
}
