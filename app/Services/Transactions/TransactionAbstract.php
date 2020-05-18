<?php


namespace App\Services\Transactions;

use DateTime, DateInterval;

abstract class TransactionAbstract implements TransactionTypeContract
{

    /**
     * Deposit date when was created
     * @var DateTime
     */
    private $dateStart;

    /**
     * Now date to calculate start transaction
     * @var DateTime
     */
    private $dateNow;

    /**
     * Sum to calculate percents
     * @var float
     */
    private $sum;

    /**
     * Interval for start date and create deposit date
     * @var DateInterval
     */
    private $dateInterval;

    /**
     * Last day in month
     * @var DateTime
     */
    private $lastDayOfMonth;

    /**
     * Percent to calculate
     * @var int
     */
    private $percent = 0;

    private $transactionName;
    private $transactionValueString;
    /**
     * Capitalization constructor.
     * Gets Start deposit date, Now date and current summ to calculate
     * @param DateTime $dateStart
     * @param DateTime $dateNow
     * @param float $sum
     * @param int $percent
     */
    public function __construct(DateTime $dateStart, DateTime $dateNow, float $sum, int $percent = 0)
    {
        $this->dateStart = $dateStart;
        $this->dateNow = $dateNow;
        $this->sum = $sum;
        $this->dateInterval = $this->dateNow->diff($this->dateStart);
        $this->lastDayOfMonth = (clone($dateNow))->modify('last day of this month');
        $this->percent = $percent;
        $this->beforeConstruct();
    }

    abstract protected function beforeConstruct();

    /**
     * @return int
     */
    public function getPercent(): int
    {
        return $this->percent;
    }

    /**
     * @return string
     */
    public function getTransactionName():string
    {
        return $this->transactionName;
    }

    /**
     * @param string $transactionName
     */
    public function setTransactionName($transactionName): void
    {
        $this->transactionName = $transactionName;
    }

    /**
     * @return string;
     */
    public function getTransactionValueString():string
    {
        return $this->transactionValueString;
    }

    /**
     * @param string $transactionValueString
     */
    public function setTransactionValueString($transactionValueString): void
    {
        $this->transactionValueString = $transactionValueString;
    }

    /**
     * @return DateTime
     */
    protected function getDateStart(): DateTime
    {
        return $this->dateStart;
    }

    /**
     * @return DateTime
     */
    protected function getDateNow(): DateTime
    {
        return $this->dateNow;
    }

    /**
     * This method return incoming sum to calculate percents
     * @return float
     */
    protected function getSum(): float
    {
        return $this->sum;
    }

    /**
     * Return DateInterval class for date start and now date
     * @return DateInterval
     */
    protected function getDateInterval(): DateInterval
    {
        return $this->dateInterval;
    }

    /**
     * This method return true if day is last in month
     * @return bool
     */
    protected function isLastDayOfMonth()
    {
        return $this->lastDayOfMonth->diff($this->dateNow)->days === 0;
    }

    /**
     * This method return true if day is first in month
     * @return bool
     */
    protected function isFirstDayOfMonth()
    {
        return $this->dateNow->format('d') === '01';
    }
}
