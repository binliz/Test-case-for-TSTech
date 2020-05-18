<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Deposit;
use Faker\Generator as Faker;

$factory->define(Deposit::class, function (Faker $faker) {
    $created = $faker->dateTimeBetween('-15 years', '-1 year');
    $cost = (float)$faker->numberBetween(100,100000);
    return [
        'cost'=>$cost,
        'currentcost'=>$cost,
        'percent'=>$faker->numberBetween(12,50),
        'created_at'=>$created
        //
    ];
});
