<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Project;
use App\Task;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'project_id' => function(){
            return factory(Project::class)->create()->id;
        },
        'body' => $faker->sentence(),
    ];
});
