<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Category;
use App\Models\Teacher;
use App\Models\CourseSection;
use App\Models\CourseContent;

class CourseSeeder extends Seeder
{
    public function run()
    {
        // جلب الكاتيجوريز المطلوبة فقط
        $categories = Category::where('type','lesson')
            ->orWhereIn('ctg_key', ['universities-and-colleges-program', 'international-program'])
            ->get();

        // بيانات حقيقة لكورسات مترابطة (سيتم تكرارها لكل كاتيجوري)
        $coursesData = [
            [
                'title_en' => 'Comprehensive Mathematics Tawjihi',
                'title_ar' => 'الرياضيات التوجيهي الشامل',
                'description_en' => 'A full course covering all mathematics topics for Tawjihi students, including algebra, calculus, and statistics.',
                'description_ar' => 'كورس كامل يغطي جميع موضوعات الرياضيات لطلاب التوجيهي، يشمل الجبر والتفاضل والإحصاء.',
                'selling_price' => 250,
                'photo' => 'course-image.jpg',
            ],
            [
                'title_en' => 'English Language Skills',
                'title_ar' => 'مهارات اللغة الإنجليزية',
                'description_en' => 'This course focuses on enhancing students’ reading, writing, and listening skills for school and university exams.',
                'description_ar' => 'يركز هذا الكورس على تطوير مهارات القراءة والكتابة والاستماع للامتحانات المدرسية والجامعية.',
                'selling_price' => 180,
                'photo' => 'course-image.jpg',
            ],
            [
                'title_en' => 'Physics for Tawjihi',
                'title_ar' => 'الفيزياء للتوجيهي',
                'description_en' => 'A detailed physics course for Tawjihi students with practical examples and solved problems.',
                'description_ar' => 'كورس فيزياء تفصيلي لطلاب التوجيهي مع أمثلة تطبيقية ومسائل محلولة.',
                'selling_price' => 230,
                'photo' => 'course-image.jpg',
            ],
            [
                'title_en' => 'Chemistry Basics',
                'title_ar' => 'أساسيات الكيمياء',
                'description_en' => 'Learn the basic concepts of chemistry, including atoms, molecules, and chemical reactions.',
                'description_ar' => 'تعلم المفاهيم الأساسية للكيمياء، بما يشمل الذرات والجزيئات والتفاعلات الكيميائية.',
                'selling_price' => 200,
                'photo' => 'course-image.jpg',
            ],
        ];

        foreach ($categories as $category) {
            foreach ($coursesData as $courseData) {
                // جلب مدرس عشوائي لكل كورس
                $teacher = Teacher::inRandomOrder()->first();

                // تعديل العنوان بإضافة اسم الكاتيجوري
                $title_en = $courseData['title_en'] . ' - ' . ($category->name_en ?? $category->name_ar);
                $title_ar = $courseData['title_ar'] . ' - ' . ($category->name_ar ?? $category->name_en);

                $course = Course::create([
                    'title_en' => $title_en,
                    'title_ar' => $title_ar,
                    'description_en' => $courseData['description_en'],
                    'description_ar' => $courseData['description_ar'],
                    'selling_price' => $courseData['selling_price'],
                    'photo' => $courseData['photo'],
                    'category_id' => $category->id,
                    'teacher_id' => $teacher->id,
                ]);

                // إنشاء سكاشن حقيقة لكل كورس
                $sectionsData = [
                    [
                        'title_en' => 'Introduction',
                        'title_ar' => 'مقدمة',
                    ],
                    [
                        'title_en' => 'Main Concepts',
                        'title_ar' => 'المفاهيم الأساسية',
                    ],
                    [
                        'title_en' => 'Practice & Exercises',
                        'title_ar' => 'تدريبات وحل مسائل',
                    ],
                ];

                $sectionIds = [];
                foreach ($sectionsData as $sectionData) {
                    $section = CourseSection::create([
                        'course_id' => $course->id,
                        'parent_id' => null,
                        'title_en' => $sectionData['title_en'],
                        'title_ar' => $sectionData['title_ar'],
                    ]);
                    $sectionIds[] = $section->id;
                }

                // محتوى فيديو (يوتيوب)
                CourseContent::create([
                    'title_en' => 'Course Overview',
                    'title_ar' => 'نظرة عامة على الكورس',
                    'content_type' => 'video',
                    'is_free' => 1,
                    'order' => 1,
                    'video_type' => 'youtube',
                    'video_url' => 'https://youtu.be/FB3JCwk0my4?list=RDMMFB3JCwk0my4&t=19',
                    'video_duration' => 600,
                    'file_path' => null,
                    'pdf_type' => null,
                    'course_id' => $course->id,
                    'section_id' => $sectionIds[0],
                ]);

                // محتوى PDF
                CourseContent::create([
                    'title_en' => 'Summary Notes',
                    'title_ar' => 'ملخصات الدرس',
                    'content_type' => 'pdf',
                    'is_free' => 2,
                    'order' => 2,
                    'video_type' => null,
                    'video_url' => null,
                    'video_duration' => null,
                    'file_path' => 'https://course-video123.b-cdn.net/bank_question/5/1755313617_e4MadwfMVz.pdf',
                    'pdf_type' => 'notes',
                    'course_id' => $course->id,
                    'section_id' => $sectionIds[1],
                ]);

                // محتوى فيديو (bunny)
                CourseContent::create([
                    'title_en' => 'Practical Examples',
                    'title_ar' => 'أمثلة تطبيقية',
                    'content_type' => 'video',
                    'is_free' => 2,
                    'order' => 3,
                    'video_type' => 'bunny',
                    'video_url' => 'https://course-video123.b-cdn.net/courses_contents/1/1755241495_g0zWdbLysC.mp4',
                    'video_duration' => 750,
                    'file_path' => null,
                    'pdf_type' => null,
                    'course_id' => $course->id,
                    'section_id' => $sectionIds[2],
                ]);

                // محتوى PDF (واجب)
                CourseContent::create([
                    'title_en' => 'Homework Sheet',
                    'title_ar' => 'ورقة واجب',
                    'content_type' => 'pdf',
                    'is_free' => 1,
                    'order' => 4,
                    'video_type' => null,
                    'video_url' => null,
                    'video_duration' => null,
                    'file_path' => 'https://course-video123.b-cdn.net/bank_question/5/1755313617_e4MadwfMVz.pdf',
                    'pdf_type' => 'homework',
                    'course_id' => $course->id,
                    'section_id' => $sectionIds[2],
                ]);
            }
        }
    }
}
