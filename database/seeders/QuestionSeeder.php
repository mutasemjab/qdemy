<?php
namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Database\Seeder;
use Faker\Factory;

class QuestionSeeder extends Seeder
{
    public function run()
    {
        // Create 200 questions with proper relationships
        for ($q=0; $q < 200; $q++) {
            $questions = Question::factory()->count(1)->create();
            $faker     = Factory::create();
            $question  = $questions?->first();
            for ($i = 0; $i < 2; $i++) {
                if ($question && $question->type == 'multiple_choice') {
                    $correct = rand(1,4);
                    for ($i = 1; $i < 5; $i++) {
                        $option_en   = $faker->sentence;
                        $option_ar   = $faker->sentence;
                        $question_id = $question->id;
                        $order       = $i;
                        $is_correct  = true;
                        $QuestionOption = QuestionOption::create([
                            'option_en'   => $option_en,
                            'option_ar'   => $option_ar,
                            'question_id' => $question_id,
                            'order'       => $i,
                            'is_correct'  => ($correct == $i),
                        ]);
                    }
                }elseif ($question && $question->type == 'true_false') {
                    $correct = rand(0,1);
                    // Create True option
                    QuestionOption::create([
                        'option_en' => 'True',
                        'option_ar' => 'صحيح',
                        'is_correct' => ($correct == 1),
                        'order' => 1,
                        'question_id' => $question->id,
                    ]);
                    // Create False option
                    QuestionOption::create([
                        'option_en' => 'False',
                        'option_ar' => 'خطأ',
                        'is_correct' => ($correct != 1),
                        'order' => 2,
                        'question_id' => $question->id,
                    ]);
                }
            }
        }

    }
}
