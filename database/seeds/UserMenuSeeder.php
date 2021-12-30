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
            // ['user_id' => 35, 'menu_id' => 6],
            // ['user_id' => 52, 'menu_id' => 6],
            ['user_id' => 53, 'menu_id' => 7],
            // ['user_id' => 100, 'menu_id' => 6],
            // ['user_id' => 119, 'menu_id' => 6],
            // ['user_id' => 145, 'menu_id' => 6],
            // ['user_id' => 198, 'menu_id' => 6],
            // ['user_id' => 219, 'menu_id' => 6],
            // ['user_id' => 228, 'menu_id' => 6],
            // ['user_id' => 249, 'menu_id' => 6],
            // ['user_id' => 251,'menu_id' => 6],
            // ['user_id' => 359,'menu_id' => 6],
            // ['user_id' => 513,'menu_id' => 6],
            // ['user_id' => 751,'menu_id' => 6],
            ['user_id' => 888,'menu_id' => 7],
            // ['user_id' => 933,'menu_id' => 6],
            // ['user_id' => 936,'menu_id' => 6],
            // ['user_id' => 941,'menu_id' => 6],
            // ['user_id' => 1010,'menu_id' => 6],
            // ['user_id' => 1015,'menu_id' => 6],
            // ['user_id' => 1036,'menu_id' => 6],
            ['user_id' => 1086,'menu_id' => 7],
            // ['user_id' => 1093,'menu_id' => 6],
            // ['user_id' => 1360,'menu_id' => 6],
            ['user_id' => 1376,'menu_id' => 7],
            ['user_id' => 1657,'menu_id' => 7],
            ['user_id' => 1929,'menu_id' => 7],
            // ['user_id' => 2650,'menu_id' => 6],
            // ['user_id' => 2651,'menu_id' => 6],
            // ['user_id' => 1519,'menu_id' => 6],
        ];

        foreach($data as $row){
            UserMenu::create($row);
        }
    }
}
