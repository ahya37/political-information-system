<?php

use App\DapilArea;
use Illuminate\Database\Seeder;

class DapilAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['dapil_id' => 14,'district_id' => 3601192],
            ['dapil_id' => 14,'district_id' => 3601190],
            ['dapil_id' => 14,'district_id' => 3601171],
            ['dapil_id' => 14,'district_id' => 3601191],
            ['dapil_id' => 14,'district_id' => 3601181],
            ['dapil_id' => 14,'district_id' => 3601180],

            ['dapil_id' => 15,'district_id' => 3601170],
            ['dapil_id' => 15,'district_id' => 3601160],
            ['dapil_id' => 15,'district_id' => 3601161],
            ['dapil_id' => 15,'district_id' => 3601150],
            ['dapil_id' => 15,'district_id' => 3601172],
           

            ['dapil_id' => 16,'district_id' => 3601071],
            ['dapil_id' => 16,'district_id' => 3601090],
            ['dapil_id' => 16,'district_id' => 3601080],
            ['dapil_id' => 16,'district_id' => 3601072],
            ['dapil_id' => 16,'district_id' => 3601070],
            ['dapil_id' => 16,'district_id' => 3601040],
            

            ['dapil_id' => 17,'district_id' => 3601060],
            ['dapil_id' => 17,'district_id' => 3601061],
            ['dapil_id' => 17,'district_id' => 3601050],
            ['dapil_id' => 17,'district_id' => 3601020],
            ['dapil_id' => 17,'district_id' => 3601030],
            ['dapil_id' => 17,'district_id' => 3601031],
            ['dapil_id' => 17,'district_id' => 3601010],
           

            ['dapil_id' => 18,'district_id' => 3601121],
            ['dapil_id' => 18,'district_id' => 3601120],
            ['dapil_id' => 18,'district_id' => 3601110],
            ['dapil_id' => 18,'district_id' => 3601111],
            ['dapil_id' => 18,'district_id' => 3601112],

            ['dapil_id' => 19,'district_id' => 3601131],
            ['dapil_id' => 19,'district_id' => 3601101],
            ['dapil_id' => 19,'district_id' => 3601130],
            ['dapil_id' => 19,'district_id' => 3601140],
            ['dapil_id' => 19,'district_id' => 3601141],
            ['dapil_id' => 19,'district_id' => 3601100],
            
        ];

        foreach ($data as $val) {
            DapilArea::create($val);
        }
    }
}
