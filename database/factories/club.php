<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Club;
use App\Models\Category;
use Faker\Generator as Faker;

$factory->define(Club::class, function () {
    	$faker = \Faker\Factory::create('vi_VN');
	    $faker->addProvider(new \Faker\Provider\Lorem($faker));
	    $faker->addProvider(new \Faker\Provider\Image($faker));
		$title=$faker->text(30);
	    return [
	        'name' => $title,
	        'image'=>  $faker->imageUrl(600,400,'food'),
	        'description'=>$faker->text(30),
	        'content' => $faker->text(250), // password
	        'status' => true
	    ];
    
});
