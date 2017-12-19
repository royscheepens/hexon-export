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
            $table->unsignedInteger('resource_id')->unique();

            $table->string('brand');
            $table->string('model');
            $table->string('type')->nullable();
            $table->string('slug')->unique();

            // todo: check of ook de maand erbij wordt gegeven
            $table->char('build_year', 4)->nullable();

            $table->string('license_plate');
            $table->date('apk_until')->nullable();

            // todo: description
            // todo: weight
            // todo: accelleration
            // todo: apk tot

            $table->string('bodywork')->nullable();
            $table->string('color')->nullable();
            $table->string('base_color')->nullable();
            $table->string('lacquer')->nullable();
            $table->string('lacquer_type')->nullable();
            $table->unsignedInteger('num_doors')->nullable();
            $table->unsignedInteger('num_seats')->nullable();

            $table->enum('fuel_type', ['B', 'D', 'L', '3', 'E', 'H', 'C', 'O'])->nullable();
            $table->unsignedInteger('mileage')->nullable();
            $table->enum('mileage_unit', ['K', 'M'])->nullable();
            $table->unsignedInteger('range')->nullable();

            $table->enum('transmission', ['H', 'A', 'S', 'C'])->nullable();
            $table->unsignedInteger('num_gears')->nullable();

            $table->unsignedInteger('mass')->nullable();
            $table->unsignedInteger('max_towing_weight')->nullable();
            $table->unsignedInteger('num_cylinders')->nullable();
            $table->unsignedInteger('cylinder_capacity')->nullable();

            $table->unsignedInteger('power_hp')->nullable();
            $table->unsignedInteger('power_kw')->nullable();

            $table->unsignedInteger('top_speed')->nullable();

            $table->unsignedInteger('fuel_capacity')->nullable();
            $table->float('fuel_consumption_avg', 4, 2)->nullable();
            $table->float('fuel_consumption_city', 4, 2)->nullable();
            $table->float('fuel_consumption_highway', 4, 2)->nullable();
            $table->enum('energy_label', ['A', 'B', 'C', 'D', 'E', 'F', 'G'])->nullable();
            $table->unsignedInteger('co2_emission')->nullable();

            $table->enum('vat_margin', ['B', 'M'])->nullable();
            $table->unsignedInteger('vehicle_tax')->nullable();
            $table->unsignedInteger('delivery_costs')->nullable();

            $table->unsignedInteger('price')->nullable();
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
