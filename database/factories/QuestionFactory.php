<?php
namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    public function definition()
    {
        $questionTypes = ['multiple_choice', 'true_false', 'essay'];
        $type = $this->faker->randomElement($questionTypes);

        // توليد سؤال عشوائي عام (سيتم تعديله في السيدر)
        return [
            'title_en' => $this->faker->sentence(3),
            'title_ar' => 'سؤال: ' . $this->faker->word(),
            'question_en' => $this->faker->sentence(),
            'question_ar' => $this->faker->sentence(),
            'type' => $type,
            'grade' => $this->faker->randomFloat(2, 0.5, 5),
            'explanation_en' => $this->faker->optional(0.7)->paragraph(),
            'explanation_ar' => $this->faker->optional(0.7)->paragraph(),
            'course_id' => Course::inRandomOrder()->first()->id,
            'created_by' => $this->faker->optional(0.8)->passthrough(User::where('role_name','student')->inRandomOrder()->first()->id),
            'created_by_admin' => $this->faker->optional(0.2)->passthrough(Admin::inRandomOrder()->first()->id),
        ];
    }
}
