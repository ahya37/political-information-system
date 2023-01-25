<?php

use Illuminate\Database\Seeder;
use App\OrgDiagram;

class OrgDiagramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['regency_id' => 3602, 'dapil_id' => 12, 'district_id' => 3602011, 'village_id' => 3602011002,'parent' => null, 'title' => 'KOR.PUSAT','user_id' => 53, 'name' => 'SARIP PUDIN','image' => 'assets/user/photo/gufSpBkTeSf1lFU324h6xzEVkugTCdO96D48yQIz.jpg'],
            ['regency_id' => 3602, 'dapil_id' => 12, 'district_id' => 3602011, 'village_id' => 3602011002,'parent' => 1, 'title' => 'KOR.PUSAT','user_id' => 55, 'name' => 'SARI YANTIN','image' => 'assets/user/photo/622166f7afd0e.jpg'],
        ];

        foreach ($data as $value) {
            OrgDiagram::create($value);
        }
    }
}
