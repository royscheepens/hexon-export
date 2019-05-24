<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOccasionsAddWeblabel extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hexon_occasions', function ($table) {
            $table->string('nap_weblabel')->nullable();
            $table->string('datum_deel_1', 10)->change();
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
            $table->dropColumn('nap_weblabel');
        });
    }

}
