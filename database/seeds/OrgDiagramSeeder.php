<?php

use Illuminate\Database\Seeder;
use App\OrgDiagram;
use App\OrgDiagramVillage;

class OrgDiagramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $data = [
        //     ['regency_id' => 3602, 'dapil_id' => 12, 'district_id' => 3602011, 'village_id' => 3602011002,'parent' => null, 'title' => 'KOR.PUSAT','user_id' => 53, 'name' => 'SARIP PUDIN','image' => 'assets/user/photo/gufSpBkTeSf1lFU324h6xzEVkugTCdO96D48yQIz.jpg'],
        //     ['regency_id' => 3602, 'dapil_id' => 12, 'district_id' => 3602011, 'village_id' => 3602011002,'parent' => 1, 'title' => 'KOR.PUSAT','user_id' => 55, 'name' => 'SARI YANTIN','image' => 'assets/user/photo/622166f7afd0e.jpg'],
        // ];

        // foreach ($data as $value) {
        //     OrgDiagram::create($value);
        // }
        $data = [
            ['idx' => '3602011001.KORRT','title' => 'KORRT','name' => 'KORTE','base' => 'KORRT','regency_id' => 3602, 'district_id' => 3602011,'village_id' => 3602011001],
            ['idx' => '3602011001.KORRT.1','pidx' => '3602011001.KORRT','title' => 'RT 1','base' => 'KORRT','regency_id' => 3602, 'district_id' => 3602011,'village_id' => 3602011001],
            ['idx' => '3602011001.KORRT.2','pidx' => '3602011001.KORRT','title' => 'RT 2','base' => 'KORRT','regency_id' => 3602, 'district_id' => 3602011,'village_id' => 3602011001],
            ['idx' => '3602011001.KORRT.3','pidx' => '3602011001.KORRT','title' => 'RT 2','base' => 'KORRT','regency_id' => 3602, 'district_id' => 3602011,'village_id' => 3602011001],
            ['idx' => '3602011001.KORRT.1.1','pidx' => '3602011001.KORRT.1','title' => 'KORTE','name' => 'Andri','photo' => 'https://wp-assets.highcharts.com/www-highcharts-com/blog/wp-content/uploads/2020/03/17131120/Highsoft_04074_.jpg','base' => 'KORRT','regency_id' => 3602, 'district_id' => 3602011,'village_id' => 3602011001]
        ];

        foreach ($data as $value) {
            OrgDiagramVillage::create($value);
        }
    }
}
