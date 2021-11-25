<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'id' => 16,
            'name' => 'admin',
            'email'=> 'admin@gmail.com',
            'password' => bcrypt('12345678'),
            'level' => 2
        ]);
    }
}
