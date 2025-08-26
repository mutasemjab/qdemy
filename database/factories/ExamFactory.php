<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ExamFactory extends Factory
{
    public function definition()
    {
        $startDate = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $endDate   = $this->faker->dateTimeBetween($startDate, '+2 months');

        // الحصول على بيانات موجودة
        $course   = \App\Models\Course::inRandomOrder()->first();
        $courseId = $this->faker->randomElement([0 , 0 , $course->id]);
        $section  = $courseId ? \App\Models\CourseSection::inRandomOrder()->where('course_id',$course->id)->first() : null;
        $user     = \App\Models\User::where('role_name','student')->inRandomOrder()->first();
        $admin    = \App\Models\Admin::inRandomOrder()->first();

        return [
            'title_en' => 'Exam ' . $this->faker->words(3, true),
            'title_ar' => 'امتحان ' . $this->faker->words(3, true),
            'description_en' => $this->faker->paragraph,
            'description_ar' => $this->faker->paragraph,
            'total_grade' => 0, // سيتم تحديثه لاحقًا
            'duration_minutes' => $this->faker->numberBetween(30, 180),
            'attempts_allowed' => $this->faker->numberBetween(1, 5),
            'passing_grade' => $this->faker->randomFloat(2, 50, 70),
            'shuffle_questions' => $this->faker->boolean,
            'shuffle_options' => $this->faker->boolean,
            'show_results_immediately' => $this->faker->boolean,
            'is_active' => $this->faker->boolean(80),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'course_id' =>$courseId ? $courseId : null,
            'section_id' => $section?->id,
            'created_by' => $user->id,
            'created_by_admin' => $admin->id,
        ];
    }
}
