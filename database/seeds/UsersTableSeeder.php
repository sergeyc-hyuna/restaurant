<?php

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
        DB::table('users')->insert([
            'name' => 'Manager',
            'email' => 'manager@gmail.com',
            'password' => bcrypt('123123'),
            'role' => 'manager',
        ]);

        DB::table('users')->insert([
            'name' => 'Cook',
            'email' => 'cook@gmail.com',
            'password' => bcrypt('123123'),
            'role' => 'cook',
        ]);

        DB::table('users')->insert([
            'name' => 'Waiter',
            'email' => 'waiter@gmail.com',
            'password' => bcrypt('123123'),
            'role' => 'waiter',
        ]);

        DB::table('users')->insert([
            'name' => 'Trainee',
            'email' => 'trainee@gmail.com',
            'password' => bcrypt('123123'),
        ]);
    }
}
