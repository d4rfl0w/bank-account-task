<?php

declare(strict_types=1);

namespace BankingSystem\Domain\Entity;

use BankingSystem\Domain\Exception\InsufficientBalanceException;
use BankingSystem\Domain\Exception\TransactionLimitExceededException;
use BankingSystem\Domain\ValueObject\Currency;
use DateTime;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

final class BankAccount
{
    private const TRANSACTION_FEE_PERCENT = 0.005;

    private const DAILY_TRANSACTION_LIMIT = 3;

    private string $id;

    private array $transactions = [];

    public function __construct(
        private Currency $currency,
        private float $balance = 0.0
    ) {
        $this->id = Uuid::uuid4()->toString();
        if ($balance < 0) {
            throw new \InvalidArgumentException('Initial balance cannot be negative.');
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function credit(Payment $payment): void
    {
        if (! $payment->getCurrency()->equals($this->currency)) {
            throw new InvalidArgumentException('Currency mismatch.');
        }
        $this->balance += $payment->getAmount();
        $this->transactions[] = [
            'type' => 'credit',
            'amount' => $payment->getAmount(),
            'date' => new DateTime(),
        ];
    }

    public function debit(Payment $payment): void
    {
        if (! $payment->getCurrency()->equals($this->currency)) {
            throw new InvalidArgumentException('Currency mismatch.');
        }

        $transactionFee = $payment->getAmount() * self::TRANSACTION_FEE_PERCENT;
        $totalDebitAmount = $payment->getAmount() + $transactionFee;

        if ($this->balance < $totalDebitAmount) {
            throw new InsufficientBalanceException('Insufficient balance.');
        }

        if ($this->countDailyTransactions() >= self::DAILY_TRANSACTION_LIMIT) {
            throw new TransactionLimitExceededException('Daily transaction limit exceeded.');
        }

        $this->balance -= $totalDebitAmount;
        $this->transactions[] = [
            'type' => 'debit',
            'amount' => $totalDebitAmount,
            'date' => new DateTime(),
        ];
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getTransactionHistory(): array
    {
        return array_map(fn ($tx) => [
            'type' => $tx['type'],
            'amount' => $tx['amount'],
            'date' => $tx['date']->format('Y-m-d H:i:s'),
        ], $this->transactions);
    }

    private function countDailyTransactions(): int
    {
        $today = (new DateTime())->format('Y-m-d');
        return count(array_filter($this->transactions, fn ($t) => $t['type'] === 'debit' && $t['date']->format('Y-m-d') === $today));
    }
}
