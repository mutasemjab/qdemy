<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Category;
use App\Models\CourseContent;
use App\Models\CourseSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesSeeder extends Seeder
{
    public function run()
    {
        $teachers   = Teacher::pluck('id')->toArray();
        $categories = Category::where('type','lesson')->orWhereIn('ctg_key',
                      ['universities-and-colleges-program','international-program']
        )->pluck('id')->toArray();

        $faker      = Factory::create();

        foreach ($categories as $ctg) {
            $loopLenth =  (in_array($category->ctg_key,['universities-and-colleges-program','international-program'])) ? 20 : 4;
            for ($i=0; $i < $loopLenth; $i++) {
                $course = Course::create([
                     'title_en' => $faker->sentence,
                     'title_ar' => $faker->sentence,
                     'description_en' => $faker->paragraph,
                     'description_ar' => $faker->paragraph,
                     'selling_price'  => $faker->randomFloat(2, 20, 200),
                     'photo'          => 'course-image.jpg',
                     'teacher_id'     => count($teachers)   > 0  ? fake()->randomElement($teachers) : null,
                     'category_id'    => $ctg,
                 ]);

                 // direct content
                $contentsCount = rand(0,2);
                for ($k = 0; $k < $contentsCount; $k++) {
                    $contentType = $faker->randomElement(['video', 'pdf', 'quiz', 'assignment']);
                    $contentData = [
                        'title_en' => $faker->sentence(3),
                        'title_ar' => $faker->sentence(3),
                        'content_type' => $contentType,
                        'is_free' => $faker->randomElement([1, 2]),
                        'order' => $k,
                        'course_id' => $course->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    // إضافة حقول إضافية حسب نوع المحتوى
                    if ($contentType === 'video') {
                        $contentData['video_type'] = $faker->randomElement(['youtube', 'bunny']);
                        $contentData['video_url'] = $contentData['video_type'] == 'youtube' ? 'https://youtu.be/FB3JCwk0my4?list=RDMMFB3JCwk0my4&t=19' : 'https://course-video123.b-cdn.net/courses_contents/1/1755241495_g0zWdbLysC.mp4';
                        $contentData['video_duration'] = '230';
                    } elseif ($contentType === 'pdf') {
                        $contentData['pdf_type'] = $faker->randomElement(['homework', 'worksheet', 'notes', 'other']);
                        $contentData['file_path'] = "uploads/{$course->id}/document_{$k}.pdf";
                    } else {
                        // للمحتوى من نوع quiz أو assignment
                        $contentData['file_path'] = "uploads/{$course->id}/content_{$k}.pdf";
                    }
                    CourseContent::create($contentData);
                }
                // إنشاء أقسام للكورس
                $sectionsCount = rand(3, 7);
                $sections = [];

                for ($j = 0; $j < $sectionsCount; $j++) {
                    $section = CourseSection::create([
                        'parent_id' => null, // سنضيف التدرج الهرمي لاحقاً
                        'course_id' => $course->id,
                        'title_en' => $faker->sentence(3),
                        'title_ar' => $faker->sentence(3),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $sections[] = $section;

                    // إنشاء محتوى لكل قسم
                    $contentsCount = rand(2, 5);
                    for ($k = 0; $k < $contentsCount; $k++) {
                        $contentType = $faker->randomElement(['video', 'pdf', 'quiz', 'assignment']);

                        $contentData = [
                            'title_en' => $faker->sentence(3),
                            'title_ar' => $faker->sentence(3),
                            'content_type' => $contentType,
                            'is_free' => $faker->randomElement([1, 2]),
                            'order' => $k,
                            'course_id' => $course->id,
                            'section_id' => $section->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];

                        // إضافة حقول إضافية حسب نوع المحتوى
                        if ($contentType === 'video') {
                            $contentData['video_type'] = $faker->randomElement(['youtube', 'bunny']);
                            $contentData['video_url'] = 'https://youtu.be/FB3JCwk0my4?list=RDMMFB3JCwk0my4&t=19';
                            $contentData['video_duration'] = '230';
                        } elseif ($contentType === 'pdf') {
                            $contentData['pdf_type'] = $faker->randomElement(['homework', 'worksheet', 'notes', 'other']);
                            $contentData['file_path'] = "uploads/{$course->id}/document_{$k}.pdf";
                        } else {
                            // للمحتوى من نوع quiz أو assignment
                            $contentData['file_path'] = "uploads/{$course->id}/content_{$k}.pdf";
                        }

                        CourseContent::create($contentData);
                    }
                }

                // إضافة تدرج هرمي لبعض الأقسام (جعل بعضها أقسام فرعية)
                if (count($sections) > 3) {
                    for ($j = 3; $j < count($sections); $j++) {
                        $parentSection = $sections[rand(0, 2)];
                        $sections[$j]->update(['parent_id' => $parentSection->id]);
                    }
                }
            }
        }
    }
}
