<?php

use Faker\Generator as Faker;

$factory->define(App\Reply::class, function (Faker $faker) {
    return [
        'track_id' => function () {
            return create(App\Track::class)->id;
        },
        'body' => $faker->text(),
    ];
});
