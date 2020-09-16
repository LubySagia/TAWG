<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\EventComment;
use App\Models\EventCommentReport;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(EventCommentReport::class, function () {
    	$faker = \Faker\Factory::create('vi_VN');
	    $faker->addProvider(new \Faker\Provider\Lorem($faker));
	    $faker->addProvider(new \Faker\Provider\Image($faker));
		$title=$faker->text(30);
	    $comment=EventComment::inRandomOrder()->first();
	    $user=User::inRandomOrder()->first();
	    return [
	        'comment_id' => $comment->id,
	        'user_id' => $user->id,
	        'content' => $faker->text(250)
	    ];
    
});
