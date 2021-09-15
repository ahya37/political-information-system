<?php

use App\UserMenu;
use Illuminate\Database\Seeder;

class UserMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $data = [
            ['user_id' => 52, 'menu_id' => 1],
            ['user_id' => 52, 'menu_id' => 2],
            ['user_id' => 119, 'menu_id' => 1],
            ['user_id' => 119, 'menu_id' => 2],
            ['user_id' => 228, 'menu_id' => 1],
            ['user_id' => 228, 'menu_id' => 2],
            ['user_id' => 198, 'menu_id' => 1],
            ['user_id' => 198, 'menu_id' => 2],
            ['user_id' => 53, 'menu_id' => 1],
            ['user_id' => 53, 'menu_id' => 2],
            ['user_id' => 100, 'menu_id' => 1],
            ['user_id' => 100, 'menu_id' => 2],
            ['user_id' => 249, 'menu_id' => 1],
            ['user_id' => 249, 'menu_id' => 2],
            ['user_id' => 145, 'menu_id' => 1],
            ['user_id' => 145, 'menu_id' => 2],
            ['user_id' => 35, 'menu_id' => 1],
            ['user_id' => 35, 'menu_id' => 2],
            ['user_id' => 251, 'menu_id' => 1],
            ['user_id' => 251, 'menu_id' => 2]
        ];

        foreach($data as $row){
            UserMenu::create($row);
        }
    }
}
