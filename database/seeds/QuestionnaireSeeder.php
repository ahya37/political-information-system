<?php

use Illuminate\Database\Seeder;
use App\Questionnaire;

class QuestionnaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Kuisioner AAW', 'number_of_respondent' => 0, 'url' => 'IxYstwi10','created_by' => 35]
        ];

        foreach ($data as $value) {
            
            Questionnaire::create($value);

        }
    }
}
