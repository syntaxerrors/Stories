<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePastesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pastes', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index();
			$table->string('name')->index();
			$table->text('text');
			$table->integer('fork');
			$table->integer('private')->index();
			$table->integer('clicks')->index();
			$table->string('slug')->index();
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
        Schema::drop('pastes');
    }

}
