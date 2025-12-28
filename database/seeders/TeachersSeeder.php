<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeachersSeeder extends Seeder
{
    public function run()
    {
        // Create a teacher user first
        $teacherUser = User::create([
            'name' => 'Dr. Hassan Ahmed',
            'role_name' => 'teacher',
            'phone' => '01099999999',
            'email' => 'hassan@example.com',
            'password' => Hash::make('password123'),
            'activate' => 1,
        ]);

        // Create teacher profile linked to user
        Teacher::create([
            'name' => 'Dr. Hassan Ahmed',
            'name_of_lesson' => 'Web Development',
            'description_en' => 'Expert in Laravel and modern web technologies',
            'description_ar' => 'خبير في Laravel والتقنيات الحديثة',
            'facebook' => 'https://facebook.com/hassan.web',
            'instagram' => 'https://instagram.com/hassan.web',
            'youtube' => 'https://youtube.com/hassan.web',
            'whataspp' => 'https://wa.me/201099999999',
            'photo' => 'teacher1.png',
            'user_id' => $teacherUser->id
        ]);

        $users = User::pluck('id')->toArray();

        $teachers = [
            [
                'name' => 'Ahmed Mohamed',
                'name_of_lesson' => 'Mathematics',
                'description_en' => 'Expert in algebra and calculus with 10 years teaching experience',
                'description_ar' => 'خبير في الجبر وحساب التفاضل والتكامل مع 10 سنوات من الخبرة في التدريس',
                'facebook' => 'https://facebook.com/ahmed.math',
                'instagram' => 'https://instagram.com/ahmed.math',
                'youtube' => 'https://youtube.com/ahmed.math',
                'whataspp' => 'https://wa.me/201000000001',
                'photo' => 'teacher1.png'
            ],
            [
                'name' => 'Sarah Johnson',
                'name_of_lesson' => 'Physics',
                'description_en' => 'Specialized in quantum mechanics and astrophysics',
                'description_ar' => 'متخصصة في ميكانيكا الكم والفيزياء الفلكية',
                'facebook' => 'https://facebook.com/sarah.physics',
                'youtube' => 'https://youtube.com/sarah.physics',
                'photo' => 'teacher1.png'
            ],
            [
                'name' => 'Mohamed Ali',
                'name_of_lesson' => 'Chemistry',
                'description_en' => 'Organic chemistry expert with industrial experience',
                'description_ar' => 'خبير في الكيمياء العضوية مع خبرة صناعية',
                'instagram' => 'https://instagram.com/mohamed.chem',
                'whataspp' => 'https://wa.me/201000000002',
                'photo' => 'teacher1.png'
            ],
            [
                'name' => 'Fatima Hassan',
                'name_of_lesson' => 'Biology',
                'description_en' => 'Molecular biology specialist with PhD from Cambridge',
                'description_ar' => 'أخصائية في علم الأحياء الجزيئي وحاصلة على الدكتوراه من كامبريدج',
                'facebook' => 'https://facebook.com/fatima.bio',
                'photo' => 'teacher1.png'
            ],
            [
                'name' => 'David Wilson',
                'name_of_lesson' => 'Computer Science',
                'description_en' => 'Software engineer with focus on AI and machine learning',
                'description_ar' => 'مهندس برمجيات متخصص في الذكاء الاصطناعي وتعلم الآلة',
                'youtube' => 'https://youtube.com/david.cs',
                'photo' => 'teacher1.png'
            ],
            [
                'name' => 'Layla Mahmoud',
                'name_of_lesson' => 'Arabic Literature',
                'description_en' => 'Expert in classical and modern Arabic literature',
                'description_ar' => 'خبيرة في الأدب العربي الكلاسيكي والحديث',
                'facebook' => 'https://facebook.com/layla.literature',
                'instagram' => 'https://instagram.com/layla.literature',
                'photo' => 'teacher1.png'
            ],
            [
                'name' => 'James Brown',
                'name_of_lesson' => 'History',
                'description_en' => 'World history specialist with focus on Middle East',
                'description_ar' => 'متخصص في التاريخ العالمي مع التركيز على الشرق الأوسط',
                'youtube' => 'https://youtube.com/james.history',
                'photo' => 'teacher1.png'
            ],
            [
                'name' => 'Amina Salah',
                'name_of_lesson' => 'French Language',
                'description_en' => 'Native French speaker with DELF certification',
                'description_ar' => 'متحدثة فرنسية أصلية وحاصلة على شهادة DELF',
                'whataspp' => 'https://wa.me/201000000003',
                'photo' => 'teacher1.png'
            ],
            [
                'name' => 'Omar Khaled',
                'name_of_lesson' => 'Engineering',
                'description_en' => 'Mechanical engineer with industrial experience',
                'description_ar' => 'مهندس ميكانيكي مع خبرة صناعية',
                'facebook' => 'https://facebook.com/omar.eng',
                'photo' => 'teacher1.png'
            ],
            [
                'name' => 'Emily Chen',
                'name_of_lesson' => 'Chinese Language',
                'description_en' => 'Native Chinese teacher with HSK certification',
                'description_ar' => 'مدرسة صينية أصلية وحاصلة على شهادة HSK',
                'instagram' => 'https://instagram.com/emily.chinese',
                'photo' => 'teacher1.png'
            ],
            [
                'name' => 'Karim Adel',
                'name_of_lesson' => 'Economics',
                'description_en' => 'PhD in Economics with focus on development economics',
                'description_ar' => 'دكتوراه في الاقتصاد مع التركيز على اقتصاديات التنمية',
                'youtube' => 'https://youtube.com/karim.economics',
                'photo' => 'teacher1.png'
            ],
            [
                'name' => 'Nourhan Samir',
                'name_of_lesson' => 'Art',
                'description_en' => 'Professional artist and art history expert',
                'description_ar' => 'فنانة محترفة وخبيرة في تاريخ الفن',
                'instagram' => 'https://instagram.com/nourhan.art',
                'photo' => 'teacher1.png'
            ],
            [
                'name' => 'Robert Taylor',
                'name_of_lesson' => 'Music',
                'description_en' => 'Classical music composer and piano instructor',
                'description_ar' => 'ملحن موسيقى كلاسيكية ومعلم بيانو',
                'facebook' => 'https://facebook.com/robert.music',
                'photo' => 'teacher1.png'
            ],
            [
                'name' => 'Heba Ismail',
                'name_of_lesson' => 'Psychology',
                'description_en' => 'Clinical psychologist with cognitive behavioral therapy specialization',
                'description_ar' => 'طبيبة نفسية إكلينيكية متخصصة في العلاج المعرفي السلوكي',
                'whataspp' => 'https://wa.me/201000000004',
                'photo' => 'teacher1.png'
            ],
            [
                'name' => 'Youssef Farid',
                'name_of_lesson' => 'Business',
                'description_en' => 'MBA holder with 15 years corporate experience',
                'description_ar' => 'حاصل على MBA مع 15 سنة من الخبرة في الشركات',
                'youtube' => 'https://youtube.com/youssef.business',
                'photo' => 'teacher1.png'
            ],
            [
                'name' => 'Sophia Lee',
                'name_of_lesson' => 'Korean Language',
                'description_en' => 'Native Korean teacher with TOPIK certification',
                'description_ar' => 'مدرسة كورية أصلية وحاصلة على شهادة TOPIK',
                'instagram' => 'https://instagram.com/sophia.korean',
                'photo' => 'teacher1.png'
            ],
            [
                'name' => 'Tarek Nabil',
                'name_of_lesson' => 'Philosophy',
                'description_en' => 'PhD in Philosophy specializing in ethics and logic',
                'description_ar' => 'دكتوراه في الفلسفة متخصص في الأخلاق والمنطق',
                'facebook' => 'https://facebook.com/tarek.philosophy',
                'photo' => 'teacher1.png'
            ],
            [
                'name' => 'Mona Gamal',
                'name_of_lesson' => 'Geography',
                'description_en' => 'Expert in physical and human geography',
                'description_ar' => 'خبيرة في الجغرافيا الطبيعية والبشرية',
                'youtube' => 'https://youtube.com/mona.geography',
                'photo' => 'teacher1.png'
            ],
            [
                'name' => 'Patrick O\'Connor',
                'name_of_lesson' => 'English Literature',
                'description_en' => 'Shakespeare specialist with Oxford education',
                'description_ar' => 'متخصص في شكسبير مع تعليم في أكسفورد',
                'whataspp' => 'https://wa.me/201000000005',
                'photo' => 'teacher1.png'
            ],
            [
                'name' => 'Dalia Wael',
                'name_of_lesson' => 'Political Science',
                'description_en' => 'International relations expert with UN experience',
                'description_ar' => 'خبيرة في العلاقات الدولية مع خبرة في الأمم المتحدة',
                'instagram' => 'https://instagram.com/dalia.politics',
                'photo' => 'teacher1.png'
            ]
        ];

        foreach ($teachers as $teacher) {
            Teacher::create([
                'name' => $teacher['name'],
                'name_of_lesson' => $teacher['name_of_lesson'],
                'description_en' => $teacher['description_en'],
                'description_ar' => $teacher['description_ar'],
                'facebook' => $teacher['facebook'] ?? null,
                'instagram' => $teacher['instagram'] ?? null,
                'youtube' => $teacher['youtube'] ?? null,
                'whataspp' => $teacher['whataspp'] ?? null,
                'photo' => $teacher['photo'],
                'user_id' => count($users) > 0 ? fake()->randomElement($users) : null
            ]);
        }
    }
}
