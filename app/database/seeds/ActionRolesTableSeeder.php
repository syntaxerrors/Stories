<?php

class ActionRolesTableSeeder extends Seeder {

    public function run()
    {
    	// Uncomment the below to wipe the table clean before populating
    	DB::table('action_roles')->truncate();

        $action_roles = array(
            // Anima player
            array('action_id' => '1', 'role_id' => '1'),
            // Anima GM
            array('action_id' => '1', 'role_id' => '2'),
            array('action_id' => '2', 'role_id' => '2'),
            array('action_id' => '4', 'role_id' => '2'),
            array('action_id' => '11', 'role_id' => '2'),
            // Firefly Player
            array('action_id' => '1', 'role_id' => '5'),
            // Firefly GM
            array('action_id' => '1', 'role_id' => '6'),
            array('action_id' => '3', 'role_id' => '6'),
            array('action_id' => '4', 'role_id' => '6'),
            array('action_id' => '11', 'role_id' => '6'),
            // Forum Guest
            array('action_id' => '5', 'role_id' => '7'),
            // Forum Member
            array('action_id' => '5', 'role_id' => '8'),
            array('action_id' => '8', 'role_id' => '8'),
            // Forum Game Master
            array('action_id' => '4', 'role_id' => '9'),
            array('action_id' => '5', 'role_id' => '9'),
            array('action_id' => '8', 'role_id' => '9'),
            array('action_id' => '11', 'role_id' => '9'),
            array('action_id' => '12', 'role_id' => '9'),
            // Forum Moderator
            array('action_id' => '4', 'role_id' => '10'),
            array('action_id' => '5', 'role_id' => '10'),
            array('action_id' => '7', 'role_id' => '10'),
            array('action_id' => '8', 'role_id' => '10'),
            array('action_id' => '11', 'role_id' => '10'),
            array('action_id' => '12', 'role_id' => '10'),
            // Forum Administrator
            array('action_id' => '4', 'role_id' => '11'),
            array('action_id' => '5', 'role_id' => '11'),
            array('action_id' => '6', 'role_id' => '11'),
            array('action_id' => '7', 'role_id' => '11'),
            array('action_id' => '8', 'role_id' => '11'),
            array('action_id' => '10', 'role_id' => '11'),
            array('action_id' => '11', 'role_id' => '11'),
            array('action_id' => '12', 'role_id' => '11'),
            // StygianVault Admin
            array('action_id' => '1', 'role_id' => '12'),
            array('action_id' => '2', 'role_id' => '12'),
            array('action_id' => '3', 'role_id' => '12'),
            array('action_id' => '4', 'role_id' => '12'),
            array('action_id' => '5', 'role_id' => '12'),
            array('action_id' => '6', 'role_id' => '12'),
            array('action_id' => '7', 'role_id' => '12'),
            array('action_id' => '8', 'role_id' => '12'),
            array('action_id' => '9', 'role_id' => '12'),
            array('action_id' => '10', 'role_id' => '12'),
            array('action_id' => '11', 'role_id' => '12'),
            array('action_id' => '12', 'role_id' => '12'),
        );

        // Uncomment the below to run the seeder
        DB::table('action_roles')->insert($action_roles);
    }

}