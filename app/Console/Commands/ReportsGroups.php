<?php

namespace App\Console\Commands;

use App\Services\TransactionService;
use Illuminate\Console\Command;

class ReportsGroups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:groups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command report deposit amount by clients';

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
        $list = TransactionService::getAgeGroupList();
        echo "Age group \t sum\n";
        foreach ($list as $item){
            echo $item['age_range']."\t".number_format($item['sum'],2,'.',' ')."\n";
        }
    }
}
