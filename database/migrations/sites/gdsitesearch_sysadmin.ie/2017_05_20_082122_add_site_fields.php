<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddSiteFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gdsitesearch_sysadmin_ie_sites', function ($table) {
            $table->text('tags_full')->nullable();
            $table->date('published_date')->nullable();
            $table->string('backend_dev')->nullable();
            $table->string('frontend_dev')->nullable();
            $table->text('addons')->nullable();
            $table->text('customised_addons')->nullable();

            $table->dropColumn('developers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gdsitesearch_sysadmin_ie_sites', function ($table) {
            $table->dropColumn('tags_full');
            $table->dropColumn('published_date');
            $table->dropColumn('backend_dev');
            $table->dropColumn('frontend_dev');
            $table->dropColumn('addons');
            $table->dropColumn('customised_addons');

            $table->string('developers')->nullable();
        });
    }
}
