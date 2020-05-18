<?php

namespace App\Console\Commands;

use App\Services\TransactionService;
use Illuminate\Console\Command;

class ReportsBank extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:bank';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command report bank amount by month';

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
        $list = TransactionService::getMonthList();
        echo "year \t month \t amount\n";
        foreach ($list as $item){
            echo $item->year."\t".$item->month."\t".number_format( - $item->sum,2,'.',' ')."\n";
        }
    }
}
