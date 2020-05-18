<?php


namespace App\Services;


use App\Client;
use App\Deposit;
use App\Transaction;
use App\Services\Transactions\{Capitalize, Commission};
use \DatePeriod, \DateInterval;
use Carbon\Carbon;
use DB;

class TransactionService
{
    /**
     * Start transactions Now or for date $value
     * @param Deposit $deposit
     * @param null $value
     */
    static public function startTransactionNow(Deposit $deposit, $value = null)
    {
        if ($value === null) {
            $value = new \DateTime;
        }

        $services = ['App\Services\Transactions\Capitalize', 'App\Services\Transactions\Commission'];
        $lastSum = $deposit->cost;
        $percent = $deposit->percent;
        foreach ($services as $service) {
            $serviceClass = new $service($deposit->created_at, $value, $lastSum, $percent);

            if ($serviceClass->isMatch()) {
                $startSum = $lastSum;
                $calcSum = $serviceClass->calculate();
                $lastSum += $calcSum;
                $deposit->transactions()->save(
                    new \App\Transaction([
                        'startsum'             => $startSum,
                        'newsum'               => $lastSum,
                        'transactionname'      => $serviceClass->getTransactionName(),
                        'transactionvalue'     => $calcSum,
                        'transactionvaluetext' => $serviceClass->getTransactionValueString(),
                        'created_at'           => Carbon::parse($value)
                    ])
                );
            }
        }

    }

    /**
     * Pre generate deposit from start to present Day
     * @param Deposit $deposit
     */
    static public function startForDeposit(Deposit $deposit)
    {
        $services = ['App\Services\Transactions\Capitalize', 'App\Services\Transactions\Commission'];

        $perDay = new DateInterval('P1D');
        $period = new DatePeriod($deposit->created_at, $perDay, now());
        foreach ($period as $value) {
            static::startTransactionNow($deposit, $value);
        }
    }

    /**
     * List of transaction by month
     * @return \Collectable
     */
    static public function getMonthList()
    {
        $monthly = Transaction::select(DB::raw('YEAR(created_at) as year'), DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(transactionvalue) as sum'))->groupBy('year', 'month')->orderBy('year', 'asc')->orderBy('month',
            'asc')->get();
        return $monthly;
    }

    /**
     * List of age groups
     * @return \Collectable
     */
    static public function getAgeGroupList()
    {
        $userGroup = Client::select(
            DB::raw('group_concat(id) as ids'),
            DB::raw('CASE
            when YEAR(CURDATE()) - YEAR(birthday) -
            IF(STR_TO_DATE(CONCAT(YEAR(CURDATE()), "-", MONTH(birthday), "-", DAY(birthday)) ,
            "%Y-%c-%e") > CURDATE(), 1, 0) BETWEEN 18 and 25 then "group 1"
            when YEAR(CURDATE()) - YEAR(birthday) -
            IF(STR_TO_DATE(CONCAT(YEAR(CURDATE()), "-", MONTH(birthday), "-", DAY(birthday)) ,
            "%Y-%c-%e") > CURDATE(), 1, 0) BETWEEN 25 and 50 then "group 2"
            when YEAR(CURDATE()) - YEAR(birthday) -
            IF(STR_TO_DATE(CONCAT(YEAR(CURDATE()), "-", MONTH(birthday), "-", DAY(birthday)) ,
            "%Y-%c-%e") > CURDATE(), 1, 0) > 50 then "group 3" END as age_range')
        )->groupBy('age_range')->orderBy('age_range', 'asc')->get();

        $groupCollection = collect([]);

        $userGroup->each(function ($group) use ($groupCollection) {
            $depositSum = Deposit::whereIn('client_id', explode(',', $group->ids))
                ->sum('currentcost');
            $depositCount = Deposit::whereIn('client_id', explode(',', $group->ids))
                ->count('id');
            $groupCollection->add([
                'age_range' => $group->age_range,
                'sum'       => $depositSum / $depositCount
            ]);
        });
        return $groupCollection;
    }
}
