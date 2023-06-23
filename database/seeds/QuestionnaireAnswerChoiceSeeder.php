<?php

use App\QuestionnaireAnswerChoice;
use Illuminate\Database\Seeder;

class QuestionnaireAnswerChoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            // ['questionnaire_question_id' => 3, 'answer_choice_category_id' => 1, 'number' => 1, 'created_by' => 35],
            ['questionnaire_question_id' => 3, 'answer_choice_category_id' => 2, 'number' => 2, 'created_by' => 35],
        ];

        foreach ($data as $value) {
            QuestionnaireAnswerChoice::create($value);
        }
    }
}
