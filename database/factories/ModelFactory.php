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
	static $password;

	return [
		'user_login' => $faker->username,
		'user_first_name' => $faker->firstName,
		'user_last_name' => $faker->lastName,
		'user_display_name' => $faker->firstName . ' ' . $faker->lastName,
		'user_email' => $faker->unique()->safeEmail,
		'user_password' => $password ?: $password = bcrypt('secret'),
		'user_activation_token' => str_random(20),
		'user_role' => 1,
		'user_registered' => $faker->boolean($chanceOfGettingTrue = 100),
		'remember_token' => str_random(10),
		'site' => 1,
	];
});

$factory->define(App\Page::class, function (Faker\Generator $faker) {
	return [
		'page_title' => $faker->sentence(),
		'page_content' => $faker->realText(),
		'page_date' => $faker->dateTime(),
		'page_status' => $faker->randomElement(['published', 'scheduled', 'draft', 'revision']),
		'page_author' => function () {
			return factory(App\User::class)->make()->id;
		},
		'page_type' => 'page',
		'page_slug' => $faker->slug(),
		'site' => 1,
	];
});