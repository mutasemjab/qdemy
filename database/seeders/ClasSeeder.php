<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Clas;

class ClasSeeder extends Seeder
{
    public function run()
    {
        // Ensure there are classes available
        if (Clas::count() != 0) {
            $this->command->error('there is classes found.');
            return;
        }
        DB::table('clas')->insert([
            ['name_ar' => 'الصف الأول', 'name_en' => 'First Grade', 'created_at' => now(), 'updated_at' => now()],
            ['name_ar' => 'الصف الثاني', 'name_en' => 'Second Grade', 'created_at' => now(), 'updated_at' => now()],
            ['name_ar' => 'الصف الثالث', 'name_en' => 'Third Grade', 'created_at' => now(), 'updated_at' => now()],
            ['name_ar' => 'الصف الرابع', 'name_en' => 'Fourth Grade', 'created_at' => now(), 'updated_at' => now()],
            ['name_ar' => 'الصف الخامس', 'name_en' => 'Fifth Grade', 'created_at' => now(), 'updated_at' => now()],
            ['name_ar' => 'الصف السادس', 'name_en' => 'Sixth Grade', 'created_at' => now(), 'updated_at' => now()],
            ['name_ar' => 'الصف السابع', 'name_en' => 'Seventh Grade', 'created_at' => now(), 'updated_at' => now()],
            ['name_ar' => 'الصف الثامن', 'name_en' => 'Eighth Grade', 'created_at' => now(), 'updated_at' => now()],
            ['name_ar' => 'الصف التاسع', 'name_en' => 'Ninth Grade', 'created_at' => now(), 'updated_at' => now()],
            ['name_ar' => 'الصف العاشر', 'name_en' => 'Tenth Grade', 'created_at' => now(), 'updated_at' => now()],
            ['name_ar' => 'الصف الحادي عشر', 'name_en' => 'Eleventh Grade', 'created_at' => now(), 'updated_at' => now()],
            ['name_ar' => 'الصف الثاني عشر (التوجيهي)', 'name_en' => 'Twelfth Grade (Tawjihi)', 'created_at' => now(), 'updated_at' => now()],
            ['name_ar' => 'جامعة', 'name_en' => 'University', 'created_at' => now(), 'updated_at' => now()],
        ]);
        $this->command->info('Successfully seeded 30 users with connected data!');
    }

}
