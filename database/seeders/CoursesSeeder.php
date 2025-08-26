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

        $this->main_course();

        $teachers   = Teacher::pluck('id')->toArray();
        $categories = Category::where('type','lesson')->orWhereIn('ctg_key',
                      ['universities-and-colleges-program','international-program']
        )->get();

        $faker      = Factory::create();

        foreach ($categories as $category) {
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
                     'category_id'    => $category->id,
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

    public function main_course()
    {
        // جلب كاتيجوري مناسبة للكورس (أول واحدة أو اختار بالاسم)
        $category = Category::where('type', 'lesson')->first();

        // جلب مدرس عشوائي
        $teacher = Teacher::inRandomOrder()->first();

        // إنشاء الكورس نفسه
        $course = Course::create([
            'title_en' => 'Full Stack Web Development Bootcamp',
            'title_ar' => 'معسكر تطوير الويب الشامل',
            'description_en' => "A comprehensive bootcamp covering everything you need to become a professional Full Stack Web Developer. Learn HTML, CSS, JavaScript, backend development, databases, version control, and build real-world projects from scratch. Perfect for beginners and intermediate students.",
            'description_ar' => "معسكر شامل يغطي كل ما تحتاجه لتصبح مطور ويب محترف. تعلم HTML وCSS وJavaScript، وتطوير الباك اند، وقواعد البيانات، وإدارة الإصدارات، وطبق كل ذلك في مشاريع عملية واقعية خطوة بخطوة. مناسب للمبتدئين والمتوسطين.",
            'selling_price' => 499,
            'photo' => 'course-image.jpg',
            'teacher_id' => $teacher->id,
            'category_id' => $category->id,
        ]);

        // سكاشن الكورس
        $sections = [
            [
                'title_en' => 'Introduction & Roadmap',
                'title_ar' => 'مقدمة وخريطة الطريق',
                'contents' => [
                    [
                        'title_en' => 'Welcome & What You Will Learn',
                        'title_ar' => 'مرحبًا وماذا ستتعلم معنا',
                        'content_type' => 'video',
                        'is_free' => 1,
                        'order' => 1,
                        'video_type' => 'youtube',
                        'video_url' => 'https://youtu.be/FB3JCwk0my4?list=RDMMFB3JCwk0my4&t=19',
                        'video_duration' => 230,
                        'file_path' => null,
                        'pdf_type' => null,
                    ],
                    [
                        'title_en' => 'Bootcamp Syllabus & Learning Path',
                        'title_ar' => 'خطة التعلم وهيكل المعسكر',
                        'content_type' => 'pdf',
                        'is_free' => 1,
                        'order' => 2,
                        'video_type' => null,
                        'video_url' => null,
                        'video_duration' => null,
                        'file_path' => 'https://course-video123.b-cdn.net/bank_question/5/1755313617_e4MadwfMVz.pdf',
                        'pdf_type' => 'notes',
                    ],
                ]
            ],
            [
                'title_en' => 'HTML & CSS Essentials',
                'title_ar' => 'أساسيات HTML و CSS',
                'contents' => [
                    [
                        'title_en' => 'HTML Basics & Elements',
                        'title_ar' => 'مبادئ HTML والعناصر الأساسية',
                        'content_type' => 'video',
                        'is_free' => 1,
                        'order' => 1,
                        'video_type' => 'bunny',
                        'video_url' => 'https://course-video123.b-cdn.net/courses_contents/1/1755241495_g0zWdbLysC.mp4',
                        'video_duration' => 156,
                        'file_path' => null,
                        'pdf_type' => null,
                    ],
                    [
                        'title_en' => 'CSS Styling & Layouts',
                        'title_ar' => 'تصميم الصفحات باستخدام CSS والتخطيطات',
                        'content_type' => 'video',
                        'is_free' => 2,
                        'order' => 2,
                        'video_type' => 'bunny',
                        'video_url' => 'https://course-video123.b-cdn.net/courses_contents/1/1755241495_g0zWdbLysC.mp4',
                        'video_duration' => 156,
                        'file_path' => null,
                        'pdf_type' => null,
                    ],
                    [
                        'title_en' => 'HTML & CSS Cheat Sheet',
                        'title_ar' => 'ورقة مختصرة لأهم أكواد HTML وCSS',
                        'content_type' => 'pdf',
                        'is_free' => 1,
                        'order' => 3,
                        'video_type' => null,
                        'video_url' => null,
                        'video_duration' => null,
                        'file_path' => 'https://course-video123.b-cdn.net/bank_question/5/1755313617_e4MadwfMVz.pdf',
                        'pdf_type' => 'notes',
                    ],
                    [
                        'title_en' => 'Mini Project: Personal Website',
                        'title_ar' => 'مشروع صغير: موقع شخصي',
                        'content_type' => 'video',
                        'is_free' => 2,
                        'order' => 4,
                        'video_type' => 'bunny',
                        'video_url' => 'https://course-video123.b-cdn.net/courses_contents/1/1755241495_g0zWdbLysC.mp4',
                        'video_duration' => null,
                        'file_path' => null,
                        'pdf_type' => null,
                    ],
                ]
            ],
            [
                'title_en' => 'JavaScript Programming',
                'title_ar' => 'برمجة جافا سكريبت',
                'contents' => [
                    [
                        'title_en' => 'JavaScript Syntax & Variables',
                        'title_ar' => 'أساسيات جافا سكريبت والمتغيرات',
                        'content_type' => 'video',
                        'is_free' => 1,
                        'order' => 1,
                        'video_type' => 'youtube',
                        'video_url' => 'https://youtu.be/FB3JCwk0my4?list=RDMMFB3JCwk0my4&t=19',
                        'video_duration' => 230,
                        'file_path' => null,
                        'pdf_type' => null,
                    ],
                    [
                        'title_en' => 'Functions & DOM Manipulation',
                        'title_ar' => 'الدوال والتعامل مع عناصر الصفحة',
                        'content_type' => 'video',
                        'is_free' => 2,
                        'order' => 2,
                        'video_type' => 'bunny',
                        'video_url' => 'https://course-video123.b-cdn.net/courses_contents/1/1755241495_g0zWdbLysC.mp4',
                        'video_duration' => 156,
                        'file_path' => null,
                        'pdf_type' => null,
                    ],
                    [
                        'title_en' => 'Quiz: JavaScript Basics',
                        'title_ar' => 'اختبار: أساسيات جافا سكريبت',
                        'content_type' => 'quiz',
                        'is_free' => 1,
                        'order' => 3,
                        'video_type' => null,
                        'video_url' => null,
                        'video_duration' => null,
                        'file_path' => 'https://course-video123.b-cdn.net/bank_question/5/1755313617_e4MadwfMVz.pdf',
                        'pdf_type' => 'other',
                    ],
                    [
                        'title_en' => 'JavaScript Best Practices',
                        'title_ar' => 'أفضل الممارسات في جافا سكريبت',
                        'content_type' => 'pdf',
                        'is_free' => 2,
                        'order' => 4,
                        'video_type' => null,
                        'video_url' => null,
                        'video_duration' => null,
                        'file_path' => 'https://course-video123.b-cdn.net/bank_question/5/1755313617_e4MadwfMVz.pdf',
                        'pdf_type' => 'notes',
                    ],
                ]
            ],
            [
                'title_en' => 'Backend & Databases',
                'title_ar' => 'الباك اند وقواعد البيانات',
                'contents' => [
                    [
                        'title_en' => 'Introduction to Backend',
                        'title_ar' => 'مقدمة في الباك اند',
                        'content_type' => 'video',
                        'is_free' => 1,
                        'order' => 1,
                        'video_type' => 'youtube',
                        'video_url' => 'https://youtu.be/FB3JCwk0my4?list=RDMMFB3JCwk0my4&t=19',
                        'video_duration' => 230,
                        'file_path' => null,
                        'pdf_type' => null,
                    ],
                    [
                        'title_en' => 'Working with Databases',
                        'title_ar' => 'التعامل مع قواعد البيانات',
                        'content_type' => 'video',
                        'is_free' => 2,
                        'order' => 2,
                        'video_type' => 'bunny',
                        'video_url' => 'https://course-video123.b-cdn.net/courses_contents/1/1755241495_g0zWdbLysC.mp4',
                        'video_duration' => 156,
                        'file_path' => null,
                        'pdf_type' => null,
                    ],
                    [
                        'title_en' => 'Assignment: Build a REST API',
                        'title_ar' => 'تسليم عملي: بناء REST API',
                        'content_type' => 'video',
                        'is_free' => 2,
                        'order' => 3,
                        'video_type' => 'bunny',
                        'video_url' => 'https://course-video123.b-cdn.net/courses_contents/1/1755241495_g0zWdbLysC.mp4',
                        'video_duration' => null,
                        'file_path' => null,
                        'pdf_type' => null,
                    ],
                    [
                        'title_en' => 'Backend Security Notes',
                        'title_ar' => 'ملاحظات حول أمان الباك اند',
                        'content_type' => 'pdf',
                        'is_free' => 1,
                        'order' => 4,
                        'video_type' => null,
                        'video_url' => null,
                        'video_duration' => null,
                        'file_path' => 'https://course-video123.b-cdn.net/bank_question/5/1755313617_e4MadwfMVz.pdf',
                        'pdf_type' => 'notes',
                    ],
                ]
            ],
            [
                'title_en' => 'Final Project & Deployment',
                'title_ar' => 'المشروع النهائي والنشر',
                'contents' => [
                    [
                        'title_en' => 'Building Your Portfolio Project',
                        'title_ar' => 'بناء مشروعك الشخصي',
                        'content_type' => 'video',
                        'is_free' => 2,
                        'order' => 1,
                        'video_type' => 'bunny',
                        'video_url' => 'https://course-video123.b-cdn.net/courses_contents/1/1755241495_g0zWdbLysC.mp4',
                        'video_duration' => 156,
                        'file_path' => null,
                        'pdf_type' => null,
                    ],
                    [
                        'title_en' => 'Checklist & Submission Guidelines',
                        'title_ar' => 'قائمة الفحص وإرشادات التسليم',
                        'content_type' => 'pdf',
                        'is_free' => 1,
                        'order' => 2,
                        'video_type' => null,
                        'video_url' => null,
                        'video_duration' => null,
                        'file_path' => 'https://course-video123.b-cdn.net/bank_question/5/1755313617_e4MadwfMVz.pdf',
                        'pdf_type' => 'other',
                    ],
                    [
                        'title_en' => 'Deploying to the Cloud',
                        'title_ar' => 'نشر المشروع على السحابة',
                        'content_type' => 'video',
                        'is_free' => 2,
                        'order' => 3,
                        'video_type' => 'youtube',
                        'video_url' => 'https://youtu.be/FB3JCwk0my4?list=RDMMFB3JCwk0my4&t=19',
                        'video_duration' => 230,
                        'file_path' => null,
                        'pdf_type' => null,
                    ],
                    [
                        'title_en' => 'Final Quiz',
                        'title_ar' => 'الاختبار النهائي',
                        'content_type' => 'quiz',
                        'is_free' => 1,
                        'order' => 4,
                        'video_type' => null,
                        'video_url' => null,
                        'video_duration' => null,
                        'file_path' => 'https://course-video123.b-cdn.net/bank_question/5/1755313617_e4MadwfMVz.pdf',
                        'pdf_type' => 'other',
                    ],
                ]
            ],
        ];

        // إدخال السكاشن والمحتوى
        foreach ($sections as $sectionData) {
            if (CourseSection::where('title_en', $sectionData['title_en'])->exists()){
                return;
            }
            $section = CourseSection::create([
                'course_id' => $course->id,
                'parent_id' => null,
                'title_en' => $sectionData['title_en'],
                'title_ar' => $sectionData['title_ar'],
            ]);

            foreach ($sectionData['contents'] as $contentIndex => $contentData) {
                CourseContent::create([
                    'title_en' => $contentData['title_en'],
                    'title_ar' => $contentData['title_ar'],
                    'content_type' => $contentData['content_type'],
                    'is_free' => $contentData['is_free'],
                    'order' => $contentData['order'],
                    'video_type' => $contentData['video_type'] ?? null,
                    'video_url' => $contentData['video_url'] ?? null,
                    'video_duration' => $contentData['video_duration'] ?? null,
                    'file_path' => $contentData['file_path'] ?? null,
                    'pdf_type' => $contentData['pdf_type'] ?? null,
                    'course_id' => $course->id,
                    'section_id' => $section->id,
                ]);
            }
        }
    }

}
