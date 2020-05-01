<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOccasionVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hexon_occasion_videos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('occasion_id')->index();
            $table->unsignedInteger('resource_id')->index();
            $table->string('source');
            $table->string('code');

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
        Schema::dropIfExists('hexon_occasion_videos');
    }
}
