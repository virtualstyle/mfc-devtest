<?php

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
        $admin = new User();
        $admin->name = 'Admin';
        $admin->email = 'admin@site.com';
        $admin->role = 'Admin';
        $admin->password = 'password';
        $admin->save();

        $manager = new User();
        $manager->name = 'Manager';
        $manager->email = 'manager@site.com';
        $manager->role = 'Manager';
        $manager->password = 'password';
        $manager->save();
    }
}
