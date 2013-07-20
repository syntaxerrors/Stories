<?php

class Game_typesTableSeeder extends Seeder {

    public function run()
    {
    	// Uncomment the below to wipe the table clean before populating
    	DB::table('game_types')->truncate();

        $users = array(
            array(
                'uniqueId'  =>  Str::random(10),
                'name'  => 'Anima: Beyond Fantasy',
                'keyName'  => 'ANIMA',
            ),
            array(
                'uniqueId'  =>  Str::random(10),
                'name'  => 'Firefly',
                'keyName'  => 'FIREFLY',
            ),
        );

        // Uncomment the below to run the seeder
        DB::table('game_types')->insert($users);
    }

}