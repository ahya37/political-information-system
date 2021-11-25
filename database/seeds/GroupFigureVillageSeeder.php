<?php

use App\GroupFigureVillage;
use Illuminate\Database\Seeder;

class GroupFigureVillageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['figure_id' => 1,'village_id' => 3602030001,'user' => '[{"user_id":16},{"user_id":62}]'],
        ];

        foreach ($data as $val) {
            GroupFigureVillage::create($val);
        }
    }
}
