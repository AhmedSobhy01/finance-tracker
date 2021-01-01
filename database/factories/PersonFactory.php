<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Due;
use App\Models\Person;
use Faker\Generator as Faker;

$factory->define(Person::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});

$factory->afterCreating(Person::class, function ($person, $faker) {
    $person->dues()->saveMany(factory(Due::class, 10)->make());
});