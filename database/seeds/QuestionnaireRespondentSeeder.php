<?php

use App\QuestionnaireRespondent;
use Illuminate\Database\Seeder;

class QuestionnaireRespondentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['questionnaire_id' => 1,'nik' =>'3602162738192037','name' => 'John Doe','address' => 'KP. Binuangeun','gender' => 'Laki-laki','age' => 25,'phone_number' => '081273648799','created_by' => 35]
        ];

        foreach ($data as $value) {
            QuestionnaireRespondent::create($value);
        }
    }
}
