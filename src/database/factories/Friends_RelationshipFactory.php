<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Eloquents\Friends_Relationship;
use Faker\Generator as Faker;

$factory->define(Friends_Relationship::class, function (Faker $faker) {
    return [
        //
        'own_friends_id' => factory(\App\Eloquents\Friend::class)->create()->id,
        'other_friends_id' => factory(\App\Eloquents\Friend::class)->create()->id,
    ];
});
