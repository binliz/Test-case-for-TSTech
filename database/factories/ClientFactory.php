<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Client;
use Faker\Generator as Faker;

$factory->define(Client::class, function (Faker $faker) {
    $birthday = $faker->dateTimeBetween('-70 years', '-18 years');
    $sex = $faker->numberBetween(1, 2);
    $date = new DateTime('1899-12-21');;

    $idCard =
        implode('', [
            $birthday->diff($date)->days,
            implode('', $faker->randomElements([0, 1, 2, 3, 4, 5, 6, 7, 8, 9], 4)),
            implode('',$faker->randomElements([0, 1, 2, 3, 4, 5, 6, 7, 8, 9], 1))
        ]);

    return [
        'idcode'   => $idCard,
        'name'     => $faker->firstName($sex == 1 ? 'male' : 'female'),
        'lastname' => $faker->lastName,
        'sex'      => $sex,
        'birthday' => $birthday
    ];
});
