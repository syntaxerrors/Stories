<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddGameTypeIdToHordes extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hordes', function(Blueprint $table) {
            $table->string('game_type_id', 10)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hordes', function(Blueprint $table) {
            $table->dropColumn('game_type_id');
        });
    }

}
