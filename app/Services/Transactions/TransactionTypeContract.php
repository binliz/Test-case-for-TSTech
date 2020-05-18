<?php


namespace App\Services\Transactions;

use \DateTime;

interface TransactionTypeContract
{
    public function __construct(DateTime $dateStart, DateTime $dateNow, float $sum, int $percent = 0);

    public function isMatch(): bool;

    public function calculate(): float;

    public function getTransactionName(): string;
    public function getTransactionValueString(): string;
}
