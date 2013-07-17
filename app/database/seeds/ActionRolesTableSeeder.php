<?php

class ActionRolesTableSeeder extends Seeder {

    public function run()
    {
    	// Uncomment the below to wipe the table clean before populating
    	DB::table('action_roles')->delete();

        $action_roles = array(
            array('action_id' => '1', 'role_id' => '1'),
            array('action_id' => '2', 'role_id' => '1'),
            array('action_id' => '2', 'role_id' => '2'),
        );

        // Uncomment the below to run the seeder
        DB::table('action_roles')->insert($action_roles);
    }

}