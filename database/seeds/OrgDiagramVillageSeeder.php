<?php

use Illuminate\Database\Seeder;
use App\OrgDiagramVillage;

class OrgDiagramVillageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
