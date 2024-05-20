<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // test user
        $user = new \App\Models\User;
        $user->name = 'testuser';
        $user->email = 'test@gmail.com';
        $user->role = 'admin';
        $user->password = bcrypt('secret');
        $user->save();
        $admin = new \App\Models\User;
        $admin->name = 'adminuser';
        $admin->email = 'admin@gmail.com';
        $admin->role = 'admin';
        $admin->password = bcrypt('secret');
        $admin->save();
        $user1 = new \App\Models\User;
        $user1->name = 'user';
        $user1->role = 'user';
        $user1->email = 'user@gmail.com';
        $user1->password = bcrypt('secret');
        $user1->save();
    }
}
