<?php

use App\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_role = new Role();
        $user_role->label = 'USER';
        $user_role->save();

        $admin_role = new Role();
        $admin_role->label = 'ADMIN';
        $admin_role->save();
    }
}
