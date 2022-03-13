<?php

/** @var \Illuminate\Database\Eloquent\Factory  $factory */

use Faker\Generator as Faker;
use WalkerChiu\MorphBoard\Models\Entities\Board;
use WalkerChiu\MorphBoard\Models\Entities\BoardLang;

$factory->define(Board::class, function (Faker $faker) {
    return [
        'type'           => $faker->randomElement(config('wk-core.class.morph-board.boardType')::getCodes()),
        'user_id'        => 1,
        'serial'         => $faker->isbn10,
        'identifier'     => $faker->slug,
        'is_highlighted' => $faker->boolean,
        'is_enabled'     => $faker->boolean
    ];
});

$factory->define(BoardLang::class, function (Faker $faker) {
    return [
        'code'  => $faker->locale,
        'key'   => $faker->randomElement(['name', 'board_line1', 'board_line2']),
        'value' => $faker->sentence
    ];
});
