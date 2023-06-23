<?php

use App\AnswerChoiceCategory;
use Illuminate\Database\Seeder;

class AnswerChoiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Ya', 'created_by' => 53],
            ['name' => 'Tidak', 'created_by' => 53],
        ];

        foreach ($data as $value) {
            AnswerChoiceCategory::create($value);
        }
    }
}
