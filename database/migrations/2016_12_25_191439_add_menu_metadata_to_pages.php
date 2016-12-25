<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddMenuMetadataToPages extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('pages', function ($table) {
			$table->integer('menu_id')->default(0);
			$table->integer('parent_id')->default(0);
			$table->integer('display_order')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('pages', function ($table) {
			$table->dropColumn(['menu_id', 'parent_id', 'display_order']);
		});
	}
}
