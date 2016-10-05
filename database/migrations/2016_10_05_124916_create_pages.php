<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePages extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('pages', function (Blueprint $table) {
			$table->increments('id');

			$table->string('page_slug')->unique();

			$table->enum('page_status', ['published', 'scheduled', 'draft', 'revision']);
			$table->string('page_title');
			$table->string('page_type');
			$table->integer('page_author');
			$table->dateTime('page_data');
			$table->longText('page_content');
			$table->integer('site');

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('pages');
	}
}
