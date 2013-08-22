<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddGameAttributeIdToAnimaMagicSpells extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('anima_magic_spells', function(Blueprint $table) {
            $table->dropColumn('attribute_id');
            $table->integer('game_attribute_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('anima_magic_spells', function(Blueprint $table) {
            $table->dropColumn('game_attribute_id');
            $table->string('attribute_id', 10)->index();
        });
    }

}
