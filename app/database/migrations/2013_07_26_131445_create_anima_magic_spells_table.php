<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAnimaMagicSpellsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anima_magic_spells', function(Blueprint $table) {
            $table->string('uniqueId', 10);
            $table->primary('uniqueId');
            $table->string('character_id', 10)->index();
            $table->string('attribute_id', 10)->index();
            $table->string('name');
            $table->integer('level')->nullable();
            $table->integer('useCost')->nullable();
            $table->text('stats')->nullable();
            $table->text('extra')->nullable();
            $table->boolean('approvedFlag');
            $table->string('characterType')->default('Character');
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
        Schema::drop('anima_magic_spells');
    }

}
