<?php

namespace App\Services\Transactions;

use \DateTime;

class Commission extends TransactionAbstract implements TransactionTypeContract
{
    /**
     * calculate sum to substract
     * @return float
     */
    public function calculate(): float
    {
        $sumToReturn = $this->getResultSum();
        if ($this->isPartial()) {
            return -($sumToReturn / $this->part());
        }
        return -$sumToReturn;
    }

    /**
     * Calculate sum of commission for Month
     * @return float
     */
    private function getResultSum()
    {
        // 5% от 1000 - 50$
        if ($this->getSum() <= 1000) {
            $this->setTransactionValueString('5% min 50$');
            return (float)50;
        }
        // 6% от 10000
        if ($this->getSum() <= 10000) {
            $this->setTransactionValueString('6%');
            return $this->getSum() * 6 / 100;
        }
        // 7%
        $this->setTransactionValueString('7% max 5000$');
        $percent = $this->getSum() * 7 / 100;
        return $percent > 5000 ? (float)5000 : $percent;
    }

    /**
     * start this transaction only if day is firs in month
     * @return bool
     */
    public function isMatch(): bool
    {
        if ($this->getSum() > 0) {
            if ($this->isFirstDayOfMonth()) {
                return true;
            }
        }
        return false;
    }

    /**
     * calculate division part
     * @return float
     */
    private function part()
    {
        return $this->parts = (float)((int)$this->getDateStart()->format('t') / $this->getDateInterval()->days);
    }

    /**
     * get info to calculate part of commission
     * @return bool
     */
    private function isPartial()
    {
        if ($this->getDateInterval()->days > 0 && $this->getDateInterval()->days < 31 && $this->getDateInterval()->m === 0) {
            /* last day of mont divide by days */
            return true;
        }
        return false;
    }

    protected function beforeConstruct()
    {
        $this->setTransactionName('Commision');
        $this->setTransactionValueString('5%');
    }
}
