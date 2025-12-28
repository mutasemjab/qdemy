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
        $this->call(PermissionSeeder::class);
        $this->call(ClasSeeder::class);
       // $this->call(UserSeeder::class);
        $this->call(CategoriesSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(BannedWordsSeeder::class);

       $this->call(TeachersSeeder::class);
     //  $this->call(CoursesSeeder::class);
      // $this->call(CoursesSeeder::class);
      // $this->call(QuestionSeeder::class);
       //$this->call(ExamSeeder::class);
      // $this->call(SpecialQdemySeeder::class);
       //$this->call(PackageSeeder::class);
    }
}