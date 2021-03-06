<?php

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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => str_random(10),
        //'remember_token' => str_random(10),
    ];
});

$factory->defineAs(App\User::class, 'admin', function() {
    return [
        'name' => "John DOE",
        'email' => "john.doe@example.com",
        'role' => 'admin', 
        'password' => str_random(10),
        //'remember_token' => str_random(10),
    ];
});

$factory->define(App\Project::class, function(Faker\Generator $faker) {
    return [
        'name' => $faker->sentence(rand(1, 3)),
        'due' => $faker->date(),
        'desc' => $faker->text()
    ];
});

$factory->define(App\ProjectLabel::class, function(Faker\Generator $faker) {
    return ['name' => $faker->word];
});

$factory->define(App\Activity::class, function() {
    return [
        'type' => 'foo', 
        'note' => 'bar',
        'resource_type' => 'Dummy\Type',
        'resource_id' => 007,
        
    ];
});

$factory->define(App\Task::class, function(Faker\Generator $faker) {
    return ['name' => $faker->sentence];
});