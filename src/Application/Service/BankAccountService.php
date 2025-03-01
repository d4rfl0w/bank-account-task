<?php

declare(strict_types=1);

namespace BankingSystem\Application\Service;

use BankingSystem\Domain\Entity\Payment;
use BankingSystem\Domain\Repository\BankAccountRepository;

final class BankAccountService
{
    public function __construct(private BankAccountRepository $repository)
    {
    }

    public function makePayment(string $accountId, Payment $payment): void
    {
        $account = $this->repository->findById($accountId);
        $account->debit($payment);
        $this->repository->save($account);
    }
}
