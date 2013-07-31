<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAnimaCharacterDetailsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anima_character_details', function(Blueprint $table) {
            $table->increments('id');
            $table->string('characterMorphId', 10)->index();
            $table->string('characterMorphType')->index();
            $table->string('anima_magic_type_id', 10)->index();
            $table->integer('level');
            $table->integer('experience');
            $table->integer('hitPoints');
            $table->integer('tempHitPoints');
            $table->integer('magicPoints');
            $table->integer('tempMagicPoints');
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
        Schema::drop('anima_character_details');
    }

}
