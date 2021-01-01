<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Cash;
use Faker\Generator as Faker;

$factory->define(Cash::class, function (Faker $faker) {
    return [
        'process_serial' => generateProcessSerial(),
        'amount' => $faker->randomElement([5, 10, 20, 50, 100, 200]),
        'serial_number' => $faker->randomNumber(7),
        'description' => $faker->text(30),
    ];
});
