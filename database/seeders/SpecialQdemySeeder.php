<?php

namespace Database\Seeders;

use App\Models\SpecialQdemy;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecialQdemySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialQdemies = [
            [
                'title_en' => 'Interactive Learning Experience',
                'title_ar' => 'تجربة تعلم تفاعلية',
            ],
            [
                'title_en' => 'AI-Powered Personalized Learning',
                'title_ar' => 'تعلم مخصص مدعوم بالذكاء الاصطناعي',
            ],
            [
                'title_en' => 'Live Virtual Classrooms',
                'title_ar' => 'فصول دراسية افتراضية مباشرة',
            ],
        ];

        foreach ($specialQdemies as $specialQdemy) {
            SpecialQdemy::create($specialQdemy);
        }
    }
}