<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOccasionImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hexon_occasion_images', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('occasion_id')->index();
            $table->unsignedInteger('resource_id')->index();
            $table->string('filename');

            $table->timestamps();

            $table->foreign('occasion_id')->references('id')->on('hexon_occasions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hexon_occasion_images');
    }
}
