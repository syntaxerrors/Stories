<?php

class RolesTableSeeder extends Seeder {

    public function run()
    {
    	// Uncomment the below to wipe the table clean before populating
    	DB::table('roles')->truncate();

        $roles = array(
            array(
                'group' => 'Administrator',
                'name' => 'Administrator',
                'keyName' => 'SITE_ADMIN',
                'description' => 'Grants access to control over the site and the ability to affect change.',
                'priority' => 1,
            ),
            array(
                'group' => 'Administrator',
                'name' => 'Developer',
                'keyName' => 'DEVELOPER',
                'description' => 'Full access to the site and it\'s features.',
                'priority' => 2,
            ),
            array(
                'group' => 'Forum',
                'name' => 'Forum Guest',
                'keyName' => 'FORUM_GUEST',
                'description' => 'New Forum users are assigned to this role before being approved.',
                'priority' => 1,
            ),
            array(
                'group' => 'Forum',
                'name' => 'Forum Member',
                'keyName' => 'FORUM_MEMBER',
                'description' => 'Approved Forum users.',
                'priority' => 2,
            ),
            array(
                'group' => 'Forum',
                'name' => 'Forum Moderator',
                'keyName' => 'FORUM_MOD',
                'description' => 'Grants users the ability to moderate the forums.',
                'priority' => 4,
            ),
            array(
                'group' => 'Forum',
                'name' => 'Forum Administrator',
                'keyName' => 'FORUM_ADMIN',
                'description' => 'Grants access to full control over the forums.',
                'priority' => 5,
            ),
        );

        // Uncomment the below to run the seeder
        DB::table('roles')->insert($roles);
    }

}