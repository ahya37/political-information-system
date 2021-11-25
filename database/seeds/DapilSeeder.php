<?php

use App\Dapil;
use Illuminate\Database\Seeder;

class DapilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Lebak
        $data = [
            ['name' => 'Dapil 1', 'regency_id' => 3601],
            ['name' => 'Dapil 2', 'regency_id' => 3601],
            ['name' => 'Dapil 4', 'regency_id' => 3601],
            ['name' => 'Dapil 5', 'regency_id' => 3601],
            ['name' => 'Dapil 6', 'regency_id' => 3601],
        ];

        foreach ($data as $val) {
            Dapil::create($val);
        }
    }
}
