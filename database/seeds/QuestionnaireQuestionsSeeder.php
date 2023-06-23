<?php

use App\QuestionnaireQuestion;
use Illuminate\Database\Seeder;

class QuestionnaireQuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['questionnaire_title_id' => 2, 'number' => 2,'desc' => 'Apakah Anda mendukung partai Nasdem','required' => 'Y','created_by' => 35]
        ];

        foreach ($data as $value) {
            QuestionnaireQuestion::create($value);
        }
    }
}
