<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Transaction;
use Faker\Generator as Faker;

$factory->define(Transaction::class, function (Faker $faker) {
    return [
        'process_serial' => generateProcessSerial(),
        'type' => $faker->randomElement([0, 1]),
        'amount' => $faker->randomFloat(2, 10.00, 10000.00),
        'description' => $faker->text(30),
    ];
});
