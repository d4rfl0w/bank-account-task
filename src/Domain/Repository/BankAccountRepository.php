<?php

declare(strict_types=1);

namespace BankingSystem\Domain\Repository;

use BankingSystem\Domain\Entity\BankAccount;

interface BankAccountRepository
{
    public function findById(string $id): BankAccount;

    public function save(BankAccount $account): void;
}
