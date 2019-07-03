<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOccasionsAddCabineLengte extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hexon_occasions', function ($table) {
            $table->string('cabine_lengte')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hexon_occasions', function ($table) {
            $table->dropColumn('cabine_lengte');
        });
    }

}
