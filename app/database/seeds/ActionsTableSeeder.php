<?php

class ActionsTableSeeder extends Seeder {

    public function run()
    {
    	// Uncomment the below to wipe the table clean before populating
    	DB::table('actions')->delete();

        $actions = array(
            array('name' => 'Test action 1', 'description' => 'Test action 1'),
            array('name' => 'Test action 2', 'description' => 'Test action 2'),
        );

        // Uncomment the below to run the seeder
        DB::table('actions')->insert($actions);
    }

}