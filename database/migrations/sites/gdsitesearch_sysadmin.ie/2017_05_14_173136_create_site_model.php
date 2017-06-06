<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteModel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gdsitesearch_sysadmin_ie_sites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('developers')->nullable();
            $table->string('project_managers')->nullable();
            $table->string('image')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gdsitesearch_sysadmin_ie_sites');
    }
}
