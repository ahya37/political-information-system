<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        // $this->call(EducationSeeder::class);
        // $this->call(JobSeeder::class);
        // $this->call(AdminSeeder::class);
        // $this->call(MenuSeeder::class);
        $this->call(UserMenuSeeder::class);

    }
}
