<?php

use Faker\Generator as Faker;

$factory->define(App\Reply::class, function (Faker $faker) {
    return [
        'replyable_id' => function() {
            return create(App\Track::class)->id;
        },
        'replyable_type' => App\Track::class,
        'user_id' => function () {
            return create(App\User::class)->id;
        },
        'body' => $faker->text(),
    ];
});
