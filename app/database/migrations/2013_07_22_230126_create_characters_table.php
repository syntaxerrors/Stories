<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCharactersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('characters', function(Blueprint $table) {
            $table->string('uniqueId', 10);
            $table->primary('uniqueId');
            $table->string('user_id', 10)->index();
            $table->string('parent_id', 10)->index();
            $table->string('game_type_id', 10)->index();
            $table->string('name');
            $table->string('color', 6);
            $table->boolean('hiddenFlag');
            $table->boolean('activeFlag');
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
        Schema::drop('characters');
	}

}