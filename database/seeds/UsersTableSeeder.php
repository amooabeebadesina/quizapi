<?php

use App\Role;
use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin_role = Role::where('label', 'ADMIN')->first();

        $user = new User();
        $user->first_name = 'Admin';
        $user->last_name = 'Admin';
        $user->password = sha1('admin');
        $user->email = 'admin@gmail.com';
        $user->phone = '09032525354';
        $user->role_id = $admin_role->id;
        $user->save();
    }
}
