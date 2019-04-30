<?php

use Itlead\Promocodes\Models\Promocode;
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(Promocode::class, function (Faker\Generator $faker) {
    $title = $faker->sentence;
    return [
        'code' => str_random(15),
        'type' => $faker->randomElement(['fixed', 'percent']),
        'reward' => $faker->randomNumber(2),
        'description' => $faker->sentence,
        'quantity' => $faker->randomNumber(2),
        'expires_at' => $faker->unixTime,
    ];
});

