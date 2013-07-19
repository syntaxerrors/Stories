<?php

class RolesTableSeeder extends Seeder {

    public function run()
    {
    	// Uncomment the below to wipe the table clean before populating
    	DB::table('roles')->truncate();

        $roles = array(
            array(
                'group' => 'Anima',
                'name' => 'Anima Player',
                'keyName' => 'ANIMA_PLAYER',
                'description' => 'User with the ability to participate in the Anima game.'
            ),
            array(
                'group' => 'Anima',
                'name' => 'Anima Story-Teller',
                'keyName' => 'ANIMA_ST',
                'description' => 'Grants the ability to use the Anima: GM areas.'
            ),
            array(
                'group' => 'Developer',
                'name' => 'Developer',
                'keyName' => 'DEVELOPER',
                'description' => 'Full access to the site and it\'s features.'
            ),
            array(
                'group' => 'Dreams in Digital',
                'name' => 'Dreams in Digital Administrator',
                'keyName' => 'DID_ADMIN',
                'description' => 'Grants access to full control over Dreams in Digital.'
            ),
            array(
                'group' => 'Firefly',
                'name' => 'Firefly Player',
                'keyName' => 'FIREFLY_PLAYER',
                'description' => 'User with the ability to participate in the firefly games'
            ),
            array(
                'group' => 'Firefly',
                'name' => 'Firefly Story-Teller',
                'keyName' => 'FIREFLY_ST',
                'description' => 'Grants the ability to create and run Firefly games.'
            ),
            array(
                'group' => 'Forum',
                'name' => 'Forum Guest',
                'keyName' => 'FORUM_GUEST',
                'description' => 'New Forum users are assigned to this role before being approved.'
            ),
            array(
                'group' => 'Forum',
                'name' => 'Forum Member',
                'keyName' => 'FORUM_MEMBER',
                'description' => 'Approved Forum users.'
            ),
            array(
                'group' => 'Forum',
                'name' => 'Forum Game Master',
                'keyName' => 'FORUM_GM',
                'description' => 'Grants users the ability to create and run forum games.'
            ),
            array(
                'group' => 'Forum',
                'name' => 'Forum Moderator',
                'keyName' => 'FORUM_MOD',
                'description' => 'Grants users the ability to moderate the forums.'
            ),
            array(
                'group' => 'Forum',
                'name' => 'Forum Administrator',
                'keyName' => 'FORUM_ADMIN',
                'description' => 'Grants access to full control over the forums.'
            ),
            array(
                'group' => 'StygianVault',
                'name' => 'StygianVault Administrator',
                'keyName' => 'SV_ADMIN',
                'description' => 'Grants access to control over the site and the ability to affect change.'
            )
        );

        // Uncomment the below to run the seeder
        DB::table('roles')->insert($roles);
    }

}