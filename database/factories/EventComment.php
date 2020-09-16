<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\EventComment;
use App\Models\Event;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(EventComment::class, function () {
    	$faker = \Faker\Factory::create('vi_VN');
	    $faker->addProvider(new \Faker\Provider\Lorem($faker));
	    $faker->addProvider(new \Faker\Provider\Image($faker));
		$title=$faker->text(30);
	    $event=Event::inRandomOrder()->first();
	    $user=User::inRandomOrder()->first();
	    return [
	        'event_id' => $event->id,
	        'user_id' => $user->id,
	        'content' => $faker->text(250)
	    ];
    
});
