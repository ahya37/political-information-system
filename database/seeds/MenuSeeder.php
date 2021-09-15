<?php

use App\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Profil', 'url' => 'http://127.0.0.1:8000/user/home'],
            ['name' => 'Buat Anggota Baru', 'url' => 'http://127.0.0.1:8000/user/member/create'],
            ['name' => 'Dashboard', 'url' => 'http://127.0.0.1:8000/user/dashboard'],
        ];

        foreach($data as $row){
            Menu::create($row);
        }
    }
}
