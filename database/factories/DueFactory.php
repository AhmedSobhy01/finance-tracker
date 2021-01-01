<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Due;
use Faker\Generator as Faker;

$factory->define(Due::class, function (Faker $faker) {
    return [
        'process_serial' => generateProcessSerial(),
        'type' => $faker->randomElement([0, 1]),
        'amount' => $faker->randomFloat(2, 10.00, 10000.00),
        'description' => $faker->text(30),
        'paid_at' => $faker->randomElement([$faker->time(), null]),
    ];
});