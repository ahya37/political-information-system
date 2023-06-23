<?php

use App\QuestionnaireAnswer;
use Illuminate\Database\Seeder;

class QuestionnaireAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['questionnaire_question_id' => 1, 'questionnaire_respondent_id' => 1, 'questionnaire_answer_choice_id' => 1,'number' => 1,'created_by' => 35]
        ];

        foreach ($data as $value) {
            QuestionnaireAnswer::create($value);
        }
    }
}
