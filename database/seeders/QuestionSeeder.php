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
        $faker = Factory::create();

        // أسماء الأشهر باللغتين
        $monthsEn = ['January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'August', 'September', 'October', 'November', 'December'];
        $monthsAr = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
                    'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];

        for ($q = 0; $q < 200; $q++) {
            // إنشاء السؤال باستخدام الفاكتوري
            $question = Question::factory()->create();

            // توليد تاريخ عشوائي لكل سؤال
            $randomDate = $faker->dateTimeThisYear();
            $correctDay = (int)$randomDate->format('j');
            $correctMonth = (int)$randomDate->format('n');
            $monthNameEn = $monthsEn[$correctMonth - 1];
            $monthNameAr = $monthsAr[$correctMonth - 1];

            // تحديث نص السؤال بناءً على نوعه
            if ($question->type === 'true_false') {
                // تحديد عشوائي إذا كان السؤال صحيح أم خطأ
                $isCorrect = $faker->boolean();
                $displayDay = $isCorrect ? $correctDay : $faker->numberBetween(1, 28);

                $question->update([
                    'question_en' => "Is today the $displayDay of $monthNameEn?",
                    'question_ar' => "هل اليوم هو $displayDay من $monthNameAr?"
                ]);

                // إضافة خيارات الصح/الخطأ
                QuestionOption::create([
                    'option_en' => 'True',
                    'option_ar' => 'صحيح',
                    'is_correct' => $isCorrect,
                    'order' => 1,
                    'question_id' => $question->id,
                ]);

                QuestionOption::create([
                    'option_en' => 'False',
                    'option_ar' => 'خطأ',
                    'is_correct' => !$isCorrect,
                    'order' => 2,
                    'question_id' => $question->id,
                ]);

            } elseif ($question->type === 'multiple_choice') {
                // تحديث نص سؤال الاختيار من متعدد
                $question->update([
                    'question_en' => "What is the current day of the month in $monthNameEn?",
                    'question_ar' => "ما هو يوم الشهر الحالي في $monthNameAr?"
                ]);

                // إنشاء خيارات للاختيار من متعدد
                $options = [];

                // الإجابة الصحيحة
                $options[] = [
                    'option_en' => "The $correctDay of $monthNameEn",
                    'option_ar' => "ال$correctDay من $monthNameAr",
                    'is_correct' => true
                ];

                // إنشاء إجابات خاطئة (أيام قريبة من اليوم الصحيح)
                $usedDays = [$correctDay];
                for ($i = 0; $i < 3; $i++) {
                    do {
                        $wrongDay = $correctDay + $faker->numberBetween(-7, 7);
                        if ($wrongDay < 1) $wrongDay = 1;
                        if ($wrongDay > 28) $wrongDay = 28;
                    } while (in_array($wrongDay, $usedDays));

                    $usedDays[] = $wrongDay;

                    $options[] = [
                        'option_en' => "The $wrongDay of $monthNameEn",
                        'option_ar' => "ال$wrongDay من $monthNameAr",
                        'is_correct' => false
                    ];
                }

                // خلط الخيارات
                shuffle($options);

                // حفظ الخيارات في قاعدة البيانات
                foreach ($options as $index => $option) {
                    QuestionOption::create([
                        'option_en' => $option['option_en'],
                        'option_ar' => $option['option_ar'],
                        'question_id' => $question->id,
                        'order' => $index + 1,
                        'is_correct' => $option['is_correct'],
                    ]);
                }

            } else {
                // الأسئلة المقالية
                $essayTopicsEn = [
                    "Discuss the importance of keeping track of dates in daily life.",
                    "Explain how different cultures perceive and measure time.",
                    "Describe the historical significance of calendar systems."
                ];
                $essayTopicsAr = [
                    "ناقش أهمية تتبع التواريخ في الحياة اليومية.",
                    "اشرح كيف تختلف perceptions الثقافات للوقت وقياسه.",
                    "صف الأهمية التاريخية لأنظمة التقويم."
                ];

                $question->update([
                    'question_en' => $faker->randomElement($essayTopicsEn),
                    'question_ar' => $faker->randomElement($essayTopicsAr),
                    'title_en' => $faker->sentence(3),
                    'title_ar' => 'سؤال مقالي'
                ]);
            }
        }
    }
}
