<?php

use Illuminate\Database\Seeder;
use App\QuestionnaireTitle;

class QuestionnaireTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['questionnaire_id' => 1, 'name' => 'Dukungan','created_by' => 35]
        ];

        foreach ($data as $value) {
            QuestionnaireTitle::create($value);
        }
    }
}
