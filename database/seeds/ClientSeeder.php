<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(\App\Client::class, 50)->create()->each(function ($client) {
            $dep = factory(\App\Deposit::class, rand(1, 3))->make();
            $client->deposits()->saveMany($dep);
            $dep->each(function ($deposit) {
                \App\Services\TransactionService::startForDeposit($deposit);
            });
        });
    }
}
