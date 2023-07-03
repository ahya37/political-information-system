<?php

use Illuminate\Database\Seeder;

class QuestionnaireQuestionsSeederCopy extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['questionnaire_title_id' => 1, 'number' => 1,'desc' => 'Apakah Anda mendukung H.Asep AW maju sebagai DPR Provinsi Banten','required' => 'Y','created_by' => 35]
        ];

        foreach ($data as $value) {
            QuestionnaireQuestion::create($value);
        }
    }
}
