<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAnimaCharacterSpellsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anima_character_spells', function(Blueprint $table) {
            $table->increments('id');
            $table->string('characterMorphId', 10)->index();
            $table->string('characterMorphType')->index();
            $table->string('anima_magic_spell_id', 10)->index();
            $table->string('buyCost');
            $table->text('description')->nullable();
            $table->boolean('approvedFlag')->default(0);
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
        Schema::drop('anima_character_spells');
    }

}
