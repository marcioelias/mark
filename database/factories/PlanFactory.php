<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Plan;
use Faker\Generator as Faker;

$factory->define(Plan::class, function (Faker $faker) {
    return [
        'marketplace_code' => Str::random(8),
        'plan_name' => $faker->unique()->word,
        'num_postbacks' => $faker->numberBetween(100, 500),
        'plan_value' => $faker->randomFloat(2, 100, 200),
        'created_at' => $faker->dateTimeBetween('-1 year', 'now')
    ];
});
