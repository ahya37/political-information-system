<?php

use App\Figure;
use Illuminate\Database\Seeder;

class FigureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Tokoh Masyarakat'],
            ['name' => 'Tokoh Politik'],
            ['name' => 'Pengusaha'],
            ['name' => 'Ustadz / Ulama / Kyai'],
            ['name' => 'Petani'],
            ['name' => 'Nelayan'],
            ['name' => 'Lain-lain'],
        ];

        foreach ($data as $value) {
            Figure::create($value);
        }
    }
}
