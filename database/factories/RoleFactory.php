<?php

/** @var Factory $factory */

use App\Role;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name' => $faker->colorName,
        'description' => $faker->text,
        'uuid' => $faker->uuid,
    ];
});
