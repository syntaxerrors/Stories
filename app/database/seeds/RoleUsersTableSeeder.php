<?php

class RoleUsersTableSeeder extends Seeder {

    public function run()
    {
    	// Uncomment the below to wipe the table clean before populating
    	DB::table('role_users')->truncate();

        $role_users = array(
            array('user_id' => '1', 'role_id' => '3'),
            array('user_id' => '2', 'role_id' => '3'),
            array('user_id' => '3', 'role_id' => '12'),
        );

        // Uncomment the below to run the seeder
        DB::table('role_users')->insert($role_users);
    }

}