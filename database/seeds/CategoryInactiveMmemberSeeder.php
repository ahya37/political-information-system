<?php

use Illuminate\Database\Seeder;
use App\CategoryInactiveMember;

class CategoryInactiveMmemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' =>'Meninggal','cby' => 35],
            ['name' =>'Pindah Alamat','cby' => 35],
            ['name' =>'Pindah Partai','cby' => 35],
            ['name' =>'Pindah Dukungan','cby' => 35],
        ];

        foreach ($data as $value) {
            CategoryInactiveMember::create($value);
        }
    }
}
