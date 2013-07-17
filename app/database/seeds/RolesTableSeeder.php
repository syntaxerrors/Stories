<?php

class RolesTableSeeder extends Seeder {

    public function run()
    {
    	// Uncomment the below to wipe the table clean before populating
    	DB::table('roles')->delete();

        $roles = array(
            array('name' => 'Test role 1', 'description' => 'Test role 1'),
            array('name' => 'Test role 2', 'description' => 'Test role 2'),
        );

        // Uncomment the below to run the seeder
        DB::table('roles')->insert($roles);
    }

}