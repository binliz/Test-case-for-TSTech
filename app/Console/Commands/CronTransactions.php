<?php

namespace App\Console\Commands;

use App\Deposit;
use App\Services\TransactionService;
use Illuminate\Console\Command;

class CronTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'These command need to add to cron for calculate percents once by Day';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $deposits = Deposit::all();
        $deposits->each( function ($deposit){
            TransactionService::startTransactionNow($deposit);
        });
    }
}
