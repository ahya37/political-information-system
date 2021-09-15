<?php

use Illuminate\Database\Seeder;
use App\Education;

class EducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Lain-Lain'],
            ['name' => 'TIDAK/BELUM SEKOLAH'],
            ['name' => 'SD'],
            ['name' => 'SMP'],
            ['name' => 'SMU'],
            ['name' => 'SMK'],
            ['name' => 'Diploma I'],
            ['name' => 'Diploma II'],
            ['name' => 'Diploma III'],
            ['name' => 'Diploma IV'],
            ['name' => 'Sarjana'],
            ['name' => 'Pasca Sarjana'],
            ['name' => 'Doktoral']
        ];

        foreach($data as $row){
            Education::create($row);
        }
    }
}
