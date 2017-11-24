<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOccasionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hexon_occasions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('resource_id')->index();

            $table->decimal('price', 10, 2)->unsigned();

            $table->string('brand');
            $table->string('model');

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
        Schema::dropIfExists('hexon_occasions');
    }
}
