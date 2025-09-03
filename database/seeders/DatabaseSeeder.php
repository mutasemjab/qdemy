<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET foreign_key_checks=0");
        $databaseName = DB::getDatabaseName();
        $tables       = DB::select("SELECT * FROM information_schema.tables WHERE table_schema = '$databaseName'");
        foreach ($tables as $table) {
            $name = $table->TABLE_NAME;
            // if you don't want to truncate migrations
            if ($name == 'users' || $name == 'content_user_progress' || $name == 'teachers' || $name == 'categories' || $name == 'courses' || $name == 'questions' || $name == 'exams') {
               DB::table($name)->truncate();
            }
            // if ($name == 'exams') {
            //    DB::table($name)->truncate();
            // }
        }
        DB::statement("SET foreign_key_checks=1");

        // $this->call(PermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CategoriesSeeder::class);
        $this->call(TeachersSeeder::class);
        $this->call(CoursesSeeder::class);
        $this->call(QuestionSeeder::class);
        $this->call(ExamSeeder::class);
        $this->call(SpecialQdemySeeder::class);
        $this->call(PackageSeeder::class);
    }
}
