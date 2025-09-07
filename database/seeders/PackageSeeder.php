<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Category;
use App\Models\Subject;
use App\Models\PackageCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        
        try {
            // إنشاء باقات للصفوف (type = class)
            $this->createClassPackages();
            
            // إنشاء باقات للمواد (type = subject)
            $this->createSubjectPackages();
            
            DB::commit();
            
            $this->command->info('Packages seeded successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding packages: ' . $e->getMessage());
        }
    }

    /**
     * إنشاء باقات للصفوف - كل باقة مرتبطة بعدة كاتيجوريز
     */
    private function createClassPackages()
    {
        // الحصول على كاتيجوريز من نوع class
        $classCategories = Category::where('type', 'class')->pluck('id')->toArray();
        
        if (count($classCategories) > 0) {
            // باقة الصفوف الأساسية المجمعة
            $package1 = Package::create([
                'name' => 'باقة الصفوف الأساسية المجمعة',
                'price' => 450.000,
                'description' => 'باقة شاملة للصفوف من الأول إلى الثالث الأساسي',
                'status' => 'active',
                'how_much_course_can_select' => rand(1,5rand(1,5),
                'type' => 'class',
                'image' => 'packages/elementary-bundle.jpg'
            ]);
            
            // ربط الباقة بعدة كاتيجوريز (3 صفوف)
            $selectedCategories = array_rand(array_flip($classCategories), min(3, count($classCategories)));
            if (!is_array($selectedCategories)) {
                $selectedCategories = [$selectedCategories];
            }
            
            foreach ($selectedCategories as $categoryId) {
                PackageCategory::create([
                    'package_id' => $package1->id,
                    'category_id' => $categoryId,
                    'subject_id' => null
                ]);
            }
            
            // باقة الصفوف المتوسطة
            $package2 = Package::create([
                'name' => 'باقة الصفوف المتوسطة',
                'price' => 550.000,
                'description' => 'باقة شاملة للصفوف من الرابع إلى السادس',
                'status' => 'active',
                'how_much_course_can_select' => rand(1,5),
                'type' => 'class',
                'image' => 'packages/middle-bundle.jpg'
            ]);
            
            // ربط بـ 3 كاتيجوريز مختلفة
            $selectedCategories = array_rand(array_flip($classCategories), min(3, count($classCategories)));
            if (!is_array($selectedCategories)) {
                $selectedCategories = [$selectedCategories];
            }
            
            foreach ($selectedCategories as $categoryId) {
                PackageCategory::create([
                    'package_id' => $package2->id,
                    'category_id' => $categoryId,
                    'subject_id' => null
                ]);
            }
            
            // باقة الصفوف الثانوية
            $package3 = Package::create([
                'name' => 'باقة الصفوف الثانوية الشاملة',
                'price' => 750.000,
                'description' => 'باقة شاملة للصفوف الثانوية والتوجيهي',
                'status' => 'active',
                'how_much_course_can_select' => rand(1,5),
                'type' => 'class',
                'image' => 'packages/secondary-bundle.jpg'
            ]);
            
            // ربط بـ 4 كاتيجوريز
            $selectedCategories = array_rand(array_flip($classCategories), min(4, count($classCategories)));
            if (!is_array($selectedCategories)) {
                $selectedCategories = [$selectedCategories];
            }
            
            foreach ($selectedCategories as $categoryId) {
                PackageCategory::create([
                    'package_id' => $package3->id,
                    'category_id' => $categoryId,
                    'subject_id' => null
                ]);
            }
            
            // باقة الجامعات والكليات
            $package4 = Package::create([
                'name' => 'باقة الجامعات والكليات المتكاملة',
                'price' => 900.000,
                'description' => 'باقة شاملة لطلاب الجامعات - السنوات التحضيرية',
                'status' => 'active',
                'how_much_course_can_select' => rand(1,5),
                'type' => 'class',
                'image' => 'packages/university-bundle.jpg'
            ]);
            
            // ربط بـ 5 كاتيجوريز
            $selectedCategories = array_rand(array_flip($classCategories), min(5, count($classCategories)));
            if (!is_array($selectedCategories)) {
                $selectedCategories = [$selectedCategories];
            }
            
            foreach ($selectedCategories as $categoryId) {
                PackageCategory::create([
                    'package_id' => $package4->id,
                    'category_id' => $categoryId,
                    'subject_id' => null
                ]);
            }
            
            // باقة VIP شاملة
            $package5 = Package::create([
                'name' => 'باقة VIP الشاملة',
                'price' => 1500.000,
                'description' => 'باقة VIP تشمل جميع المراحل الدراسية',
                'status' => 'active',
                'how_much_course_can_select' => rand(1,5),
                'type' => 'class',
                'image' => 'packages/vip-all-access.jpg'
            ]);
            
            // ربط بـ 8 كاتيجوريز
            $selectedCategories = array_rand(array_flip($classCategories), min(8, count($classCategories)));
            if (!is_array($selectedCategories)) {
                $selectedCategories = [$selectedCategories];
            }
            
            foreach ($selectedCategories as $categoryId) {
                PackageCategory::create([
                    'package_id' => $package5->id,
                    'category_id' => $categoryId,
                    'subject_id' => null
                ]);
            }
        }
    }

    /**
     * إنشاء باقات للمواد - كل باقة مرتبطة بعدة مواد
     */
    private function createSubjectPackages()
    {
        // الحصول على IDs المواد
        $subjectIds = Subject::pluck('id')->toArray();
        
        if (count($subjectIds) > 0) {
            // باقة المواد العلمية
            $package6 = Package::create([
                'name' => 'باقة المواد العلمية المتقدمة',
                'price' => 400.000,
                'description' => 'باقة شاملة للرياضيات والفيزياء والكيمياء والأحياء',
                'status' => 'active',
                'how_much_course_can_select' => rand(1,5),
                'type' => 'subject',
                'image' => 'packages/scientific-subjects.jpg'
            ]);
            
            // ربط بـ 4 مواد عشوائية
            $selectedSubjects = array_rand(array_flip($subjectIds), min(4, count($subjectIds)));
            if (!is_array($selectedSubjects)) {
                $selectedSubjects = [$selectedSubjects];
            }
            
            foreach ($selectedSubjects as $subjectId) {
                PackageCategory::create([
                    'package_id' => $package6->id,
                    'category_id' => null,
                    'subject_id' => $subjectId
                ]);
            }
            
            // باقة اللغات المتعددة
            $package7 = Package::create([
                'name' => 'باقة اللغات المتعددة',
                'price' => 350.000,
                'description' => 'باقة شاملة لتعلم اللغات - عربي، إنجليزي، فرنسي',
                'status' => 'active',
                'how_much_course_can_select' => rand(1,5),
                'type' => 'subject',
                'image' => 'packages/languages-bundle.jpg'
            ]);
            
            // ربط بـ 3 مواد
            $selectedSubjects = array_rand(array_flip($subjectIds), min(3, count($subjectIds)));
            if (!is_array($selectedSubjects)) {
                $selectedSubjects = [$selectedSubjects];
            }
            
            foreach ($selectedSubjects as $subjectId) {
                PackageCategory::create([
                    'package_id' => $package7->id,
                    'category_id' => null,
                    'subject_id' => $subjectId
                ]);
            }
            
            // باقة التقنية والبرمجة
            $package8 = Package::create([
                'name' => 'باقة التقنية والبرمجة',
                'price' => 500.000,
                'description' => 'باقة شاملة للبرمجة وقواعد البيانات والشبكات',
                'status' => 'active',
                'how_much_course_can_select' => rand(1,5),
                'type' => 'subject',
                'image' => 'packages/tech-programming.jpg'
            ]);
            
            // ربط بـ 5 مواد
            $selectedSubjects = array_rand(array_flip($subjectIds), min(5, count($subjectIds)));
            if (!is_array($selectedSubjects)) {
                $selectedSubjects = [$selectedSubjects];
            }
            
            foreach ($selectedSubjects as $subjectId) {
                PackageCategory::create([
                    'package_id' => $package8->id,
                    'category_id' => null,
                    'subject_id' => $subjectId
                ]);
            }
            
            // باقة المواد الأدبية
            $package9 = Package::create([
                'name' => 'باقة المواد الأدبية والإنسانية',
                'price' => 300.000,
                'description' => 'باقة شاملة للتاريخ والجغرافيا والفلسفة والأدب',
                'status' => 'active',
                'how_much_course_can_select' => rand(1,5),
                'type' => 'subject',
                'image' => 'packages/humanities.jpg'
            ]);
            
            // ربط بـ 4 مواد
            $selectedSubjects = array_rand(array_flip($subjectIds), min(4, count($subjectIds)));
            if (!is_array($selectedSubjects)) {
                $selectedSubjects = [$selectedSubjects];
            }
            
            foreach ($selectedSubjects as $subjectId) {
                PackageCategory::create([
                    'package_id' => $package9->id,
                    'category_id' => null,
                    'subject_id' => $subjectId
                ]);
            }
            
            // باقة غير نشطة للاختبار
            $package10 = Package::create([
                'name' => 'باقة تجريبية - قيد التطوير',
                'price' => 99.999,
                'description' => 'هذه باقة تجريبية قيد التطوير والاختبار',
                'status' => 'inactive',
                'how_much_course_can_select' => rand(1,5),
                'type' => 'subject',
                'image' => null
            ]);
            
            // ربط بمادتين
            $selectedSubjects = array_rand(array_flip($subjectIds), min(2, count($subjectIds)));
            if (!is_array($selectedSubjects)) {
                $selectedSubjects = [$selectedSubjects];
            }
            
            foreach ($selectedSubjects as $subjectId) {
                PackageCategory::create([
                    'package_id' => $package10->id,
                    'category_id' => null,
                    'subject_id' => $subjectId
                ]);
            }
        }
    }

    /**
     * إنشاء باقات مختلطة تحتوي على كاتيجوريز ومواد معاً
     */
    private function createMixedPackages()
    {
        $classCategories = Category::where('type', 'class')->pluck('id')->toArray();
        $subjectIds = Subject::pluck('id')->toArray();
        
        if (count($classCategories) > 0 && count($subjectIds) > 0) {
            // باقة التوجيهي الذهبية
            $package11 = Package::create([
                'name' => 'الباقة الذهبية - توجيهي + مواد تقوية',
                'price' => 1200.000,
                'description' => 'باقة متكاملة تشمل صفوف التوجيهي مع مواد التقوية الإضافية',
                'status' => 'active',
                'how_much_course_can_select' => rand(1,5),
                'type' => 'class', // النوع الأساسي class
                'image' => 'packages/golden-tawjihi.jpg'
            ]);
            
            // ربط بـ 3 كاتيجوريز
            $selectedCategories = array_rand(array_flip($classCategories), min(3, count($classCategories)));
            if (!is_array($selectedCategories)) {
                $selectedCategories = [$selectedCategories];
            }
            
            foreach ($selectedCategories as $categoryId) {
                PackageCategory::create([
                    'package_id' => $package11->id,
                    'category_id' => $categoryId,
                    'subject_id' => null
                ]);
            }
            
            // وربط بـ 4 مواد إضافية
            $selectedSubjects = array_rand(array_flip($subjectIds), min(4, count($subjectIds)));
            if (!is_array($selectedSubjects)) {
                $selectedSubjects = [$selectedSubjects];
            }
            
            foreach ($selectedSubjects as $subjectId) {
                PackageCategory::create([
                    'package_id' => $package11->id,
                    'category_id' => null,
                    'subject_id' => $subjectId
                ]);
            }
            
            // الباقة البلاتينية الشاملة
            $package12 = Package::create([
                'name' => 'الباقة البلاتينية الشاملة',
                'price' => 2000.000,
                'description' => 'أقوى باقة تشمل جميع المراحل الدراسية مع جميع المواد الإضافية',
                'status' => 'active',
                'how_much_course_can_select' => rand(1,5),
                'type' => 'class',
                'image' => 'packages/platinum-all.jpg'
            ]);
            
            // ربط بـ 10 كاتيجوريز
            $selectedCategories = array_rand(array_flip($classCategories), min(10, count($classCategories)));
            if (!is_array($selectedCategories)) {
                $selectedCategories = [$selectedCategories];
            }
            
            foreach ($selectedCategories as $categoryId) {
                PackageCategory::create([
                    'package_id' => $package12->id,
                    'category_id' => $categoryId,
                    'subject_id' => null
                ]);
            }
            
            // وربط بـ 10 مواد
            $selectedSubjects = array_rand(array_flip($subjectIds), min(10, count($subjectIds)));
            if (!is_array($selectedSubjects)) {
                $selectedSubjects = [$selectedSubjects];
            }
            
            foreach ($selectedSubjects as $subjectId) {
                PackageCategory::create([
                    'package_id' => $package12->id,
                    'category_id' => null,
                    'subject_id' => $subjectId
                ]);
            }
            
            // باقة الطالب المتفوق
            $package13 = Package::create([
                'name' => 'باقة الطالب المتفوق',
                'price' => 800.000,
                'description' => 'باقة مصممة خصيصاً للطلاب المتفوقين - صفوف متقدمة ومواد إثرائية',
                'status' => 'active',
                'how_much_course_can_select' => rand(1,5),
                'type' => 'subject', // النوع الأساسي subject
                'image' => 'packages/excellent-student.jpg'
            ]);
            
            // ربط بـ 2 كاتيجوري
            $selectedCategories = array_rand(array_flip($classCategories), min(2, count($classCategories)));
            if (!is_array($selectedCategories)) {
                $selectedCategories = [$selectedCategories];
            }
            
            foreach ($selectedCategories as $categoryId) {
                PackageCategory::create([
                    'package_id' => $package13->id,
                    'category_id' => $categoryId,
                    'subject_id' => null
                ]);
            }
            
            // وربط بـ 6 مواد
            $selectedSubjects = array_rand(array_flip($subjectIds), min(6, count($subjectIds)));
            if (!is_array($selectedSubjects)) {
                $selectedSubjects = [$selectedSubjects];
            }
            
            foreach ($selectedSubjects as $subjectId) {
                PackageCategory::create([
                    'package_id' => $package13->id,
                    'category_id' => null,
                    'subject_id' => $subjectId
                ]);
            }
        }
    }
}