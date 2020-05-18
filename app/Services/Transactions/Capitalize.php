<?php


namespace App\Services\Transactions;

use \DateTime;

class Capitalize extends TransactionAbstract implements TransactionTypeContract
{

    public function calculate(): float
    {
        return $this->getSum() * $this->getPercent() / 12 / 100;
    }

    /**
     * Test date to start calculation
     * @return bool
     */
    public function isMatch(): bool
    {
        if ($this->getSum() < 0) {
            return false;
        }
        $startDate = (int)$this->getDateStart()->format('d');
        $nowDate = (int)$this->getDateNow()->format('d');
        if ($this->getDateInterval()->days > 0) {
            /* if dates is same */
            if ($startDate === $nowDate) {
                return true;
            }

            /* if last day of mont smaller by day of create deposit */
            if ($this->isLastDayOfMonth() && ($nowDate <= $startDate)) {
                return true;
            }
        }
        return false;
    }


    protected function beforeConstruct()
    {
        $this->setTransactionName('Capitalize');
        $this->setTransactionValueString($this->getPercent()."% year");
    }
}
