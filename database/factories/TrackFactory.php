<?php

use Faker\Generator as Faker;

$factory->define(App\Track::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return create(App\User::class)->id;
        },
        'name' => $faker->sentence,
        'published' => false,
    ];
});
