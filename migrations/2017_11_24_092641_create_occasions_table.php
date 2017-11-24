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

            $table->string('brand');
            $table->string('model');
            $table->string('type');

            $table->decimal('price', 10, 2)->unsigned();

            $table->string('license_plate');
            $table->string('build_year');

            $table->unsignedInteger('mileage');
            $table->enum('mileage_unit', ['K', 'M']);

            $table->enum('fuel_type', ['B', 'D', 'L', '3', 'E', 'H', 'C', 'O'])->nullable();

            $table->enum('transmission', ['H', 'A', 'S', 'C'])->nullable();

            $table->enum('energy_label', ['A', 'B', 'C', 'D', 'E', 'F', 'G'])->nullable();

            $table->boolean('sold')->default(false)->index();

            $table->timestamp('sold_at')->nullable();

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
