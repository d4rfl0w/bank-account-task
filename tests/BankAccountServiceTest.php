<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use BankingSystem\Application\Service\BankAccountService;
use BankingSystem\Domain\Entity\BankAccount;
use BankingSystem\Domain\Entity\Payment;
use BankingSystem\Domain\ValueObject\Currency;
use BankingSystem\Domain\Exception\InsufficientBalanceException;
use BankingSystem\Domain\Exception\TransactionLimitExceededException;
use BankingSystem\Domain\Repository\BankAccountRepository;
use BankingSystem\Infrastructure\Persistence\InMemoryBankAccountRepository;

class BankAccountServiceTest extends TestCase
{
    private BankAccountService $bankAccountService;
    private BankAccountRepository $repository;

    protected function setUp(): void
    {
        $this->repository = new InMemoryBankAccountRepository();
        $this->bankAccountService = new BankAccountService($this->repository);
    }

    public function testMakePaymentSuccessfully(): void
    {
        $account = new BankAccount(new Currency("USD"), 100.0);
        $this->repository->save($account);
        $accountId = $account->getId();

        $payment = new Payment(50.0, new Currency("USD"));
        $this->bankAccountService->makePayment($accountId, $payment);

        $expectedBalance = 100.0 - (50.0 + (50.0 * 0.005));
        $updatedAccount = $this->repository->findById($accountId);

        $this->assertEquals($expectedBalance, $updatedAccount->getBalance());
    }

    public function testMakePaymentWithInsufficientBalanceThrowsException(): void
    {
        $this->expectException(InsufficientBalanceException::class);

        $account = new BankAccount(new Currency("USD"), 30.0);
        $this->repository->save($account);
        $accountId = $account->getId();

        $payment = new Payment(50.0, new Currency("USD"));
        $this->bankAccountService->makePayment($accountId, $payment);
    }

    public function testMakePaymentExceedingDailyTransactionLimitThrowsException(): void
    {
        $this->expectException(TransactionLimitExceededException::class);

        $account = new BankAccount(new Currency("USD"), 500.0);
        $this->repository->save($account);
        $accountId = $account->getId();

        for ($i = 0; $i < 4; $i++) {
            $payment = new Payment(50.0, new Currency("USD"));
            $this->bankAccountService->makePayment($accountId, $payment);
        }
    }

    public function testMakePaymentWithInvalidCurrencyThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $account = new BankAccount(new Currency("USD"), 100.0);
        $this->repository->save($account);
        $accountId = $account->getId();

        $payment = new Payment(50.0, new Currency("EUR"));
        $this->bankAccountService->makePayment($accountId, $payment);
    }

    public function testMakePaymentUpdatesRepository(): void
    {
        $account = new BankAccount(new Currency("USD"), 200.0);
        $this->repository->save($account);
        $accountId = $account->getId();

        $payment = new Payment(50.0, new Currency("USD"));
        $this->bankAccountService->makePayment($accountId, $payment);

        $updatedAccount = $this->repository->findById($accountId);
        $expectedBalance = 200.0 - (50.0 + (50.0 * 0.005));

        $this->assertEquals($expectedBalance, $updatedAccount->getBalance());
    }
}
