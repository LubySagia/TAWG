<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Event;
use App\Models\Club;
use Faker\Generator as Faker;

$factory->define(Event::class, function () {
    	$faker = \Faker\Factory::create('vi_VN');
	    $faker->addProvider(new \Faker\Provider\Lorem($faker));
	    $faker->addProvider(new \Faker\Provider\Image($faker));
	    $faker->addProvider(new \Faker\Provider\DateTime($faker));
	    $from=$faker->dateTimeBetween($startDate = 'now', $endDate = '+3 day', $timezone = null);
	    $to=$faker->dateTimeBetween($startDate = $from, $endDate = '+3 day', $timezone = null);
		$name=$faker->text(30);
	    $club=Club::inRandomOrder()->first();

	    return [
	        'name' => $name,
	        'club_id' => $club->id,
	        'image'=>  $faker->imageUrl(600,400,'food'),
	        'description'=>$faker->text(30),
	        'content' => $faker->text(250), // password
	        'status' => rand(0,1),
	        'private' => rand(0,1),
	        'number_member' => rand(0,100),
	        'from' => $from,
	        'to' => $to
	    ];
    
});
