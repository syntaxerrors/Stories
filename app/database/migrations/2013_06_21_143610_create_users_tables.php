<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTables extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function(Blueprint $table) {
            $table->increments('id');
            $table->string('uniqueId')->index();
            $table->string('username')->unique();
			$table->string('password');
			$table->string('firstName')->index();
			$table->string('lastName')->index();
            $table->string('displayName')->index();
            $table->string('location');
            $table->string('url');
			$table->integer('status_id')->index()->default(1);
            $table->string('email')->index();
			$table->timestamp('lastActive')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }

}
