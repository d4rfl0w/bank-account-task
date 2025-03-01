<?php

declare(strict_types=1);

namespace BankingSystem\Infrastructure\Persistence;

use BankingSystem\Domain\Entity\BankAccount;
use BankingSystem\Domain\Repository\BankAccountRepository;
use Exception;

class InMemoryBankAccountRepository implements BankAccountRepository
{
    private array $accounts = [];

    public function findById(string $id): BankAccount
    {
        if (! isset($this->accounts[$id])) {
            throw new Exception('Bank account not found.');
        }
        return $this->accounts[$id];
    }

    public function save(BankAccount $account): void
    {
        // Poprawiamy klucz na ID zamiast spl_object_hash()
        $this->accounts[$account->getId()] = $account;
    }
}
