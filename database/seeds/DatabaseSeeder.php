<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        // $this->call(EducationSeeder::class);
        // $this->call(JobSeeder::class);
        // $this->call(AdminSeeder::class);
        // $this->call(MenuSeeder::class);
        // $this->call(UserMenuSeeder::class);
        // $this->call(AdminRegionalVillage::class);
        // $this->call(FigureSeeder::class);
        // $this->call(GroupFigureVillageSeeder::class);
        // $this->call(DapilSeeder::class);
        // $this->call(DapilAreaSeeder::class);
        // $this->call(OrgDiagramSeeder::class);
        // $this->call(OrgDiagramVillageSeeder::class);
        // $this->call(CategoryInactiveMmemberSeeder::class);
        // $this->call(EventCategorySeeder::class);
        // $this->call(TpsSeeder::class);
        // $this->call(QuestionnaireSeeder::class);
        // $this->call(QuestionnaireTitleSeeder::class);
        $this->call(QuestionnaireQuestionsSeeder::class);

    }
}
