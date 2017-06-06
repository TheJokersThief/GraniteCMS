<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('users', function (Blueprint $table) {
			$table->increments('id');

			$table->string('user_login');
			$table->string('user_first_name');
			$table->string('user_last_name');
			$table->string('user_display_name');
			$table->string('user_email');
			$table->string('password');
			$table->string('user_activation_token');

			$table->integer('user_role');
			$table->integer('site');

			$table->boolean('user_registered');

			$table->unique(['user_email', 'site']);

			$table->rememberToken();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('users');
	}
}
