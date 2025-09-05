<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\Category;
use App\Models\CategorySubject;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Main Programs (Level 0) - type: major
        $programs = [
            [
                'name_ar' => 'برنامج التوجيهي والثانوي',
                'name_en' => 'Tawjihi and Secondary Program',
                'icon'    => 'fas fa-graduation-cap',
                'color'   => '#e74c3c',
                'sort_order' => 1,
                'type'    => 'major',
            ],
            [
                'name_ar' => 'برنامج الصفوف الأساسية',
                'name_en' => 'Elementary Grades Program',
                'icon' => 'fas fa-school',
                'color' => '#3498db',
                'sort_order' => 2,
                'type' => 'major',
            ],
            [
                'name_ar' => 'برنامج الجامعات والكليات',
                'name_en' => 'Universities and Colleges Program',
                'icon' => 'fas fa-university',
                'color' => '#9b59b6',
                'sort_order' => 3,
                'type' => 'major',
            ],
            [
                'name_ar' => 'البرنامج الدولي',
                'name_en' => 'International Program',
                'icon' => 'fas fa-globe',
                'color' => '#f39c12',
                'sort_order' => 4,
                'type' => 'major',
            ]
        ];
        foreach ($programs as $program) {
            $createdProgram = Category::create($program);
            // Add specific content for each program
            $this->createProgramContent($createdProgram);
        }
    }

    /**
     * Create content for each main program
    */
    private function createProgramContent($mainProgram)
    {
        switch ($mainProgram->name_ar) {
            case 'برنامج الصفوف الأساسية':
                $this->createElementaryProgram($mainProgram->id);
                break;
            case 'برنامج التوجيهي والثانوي':
                $this->createTawjihiProgram($mainProgram->id);
                break;
            // international program has no subcategories
            case 'البرنامج الدولي':
                $this->createInternationalSubjects($mainProgram->id);
                break;
            // Universities program has no subcategories
            case 'برنامج الجامعات والكليات':
                // Add university subjects directly
                $this->createFacultySubjects($mainProgram->id);
            break;
        }
    }

    /**
     * Create Tawjihi Program structure
     */
    private function createTawjihiProgram($parentId)
    {
        // Level 1: Tawjihi subdivisions - type: class
        $tawjihiSubs = [
            [
                'name_ar' => 'توجيهي 2009',
                'name_en' => 'Tawjihi 2009',
                'ctg_key' => 'first_year',
                'level'   => 'tawjihi_grade',
                'icon'    => 'fas fa-book',
                'color'   => '#e74c3c',
                'sort_order' => 1,
                'parent_id'  => $parentId,
                'type'       => 'class'
            ],
            [
                'name_ar' => 'توجيهي 2008',
                'name_en' => 'Tawjihi 2008',
                'ctg_key' => 'final_year',
                'level'   => 'tawjihi_grade',
                'icon'    => 'fas fa-book-open',
                'color'   => '#c0392b',
                'sort_order' => 2,
                'parent_id'  => $parentId,
                'type'       => 'class'
            ],
            [
                'name_ar' => 'النظام المهني',
                'name_en' => 'Vocational System',
                'level' => 'tawjihi_grade',
                'color' => '#8e44ad',
                'is_active' => 0,
                'sort_order' => 3,
                'parent_id' => $parentId,
                'type' => 'class'
            ]
        ];

        foreach ($tawjihiSubs as $sub) {
            $createdSub = Category::create($sub);
            if ($sub['name_ar'] === 'توجيهي 2009') {
                $this->createTawjihi2009Content($createdSub->id);
            } elseif ($sub['name_ar'] === 'توجيهي 2008') {
                $this->createTawjihi2008Content($createdSub->id);
            } elseif ($sub['name_ar'] === 'النظام المهني') {
                $this->createVocationalSubjects($createdSub->id);
            }
        }
    }

    /**
     * Create Tawjihi 2009 content
    */
    private function createTawjihi2009Content($parentId)
    {
        $programm_id = Category::where('name_en','Tawjihi and Secondary Program')->first()?->id;

        $subjects = [
            [ 'name_ar' => 'اللغة العربية', 'name_en' => 'Arabic Language', 'icon' => 'fas fa-font', 'color' => '#e74c3c'],
            [ 'name_ar' => 'اللغة الإنجليزية', 'name_en' => 'English Language', 'icon' => 'fas fa-language', 'color' => '#3498db'],
            [ 'name_ar' => 'الرياضيات', 'name_en' => 'Mathematics', 'icon' => 'fas fa-calculator', 'color' => '#f39c12'],
            [ 'name_ar' => 'العلوم', 'name_en' => 'Sciences', 'icon' => 'fas fa-microscope', 'color' => '#27ae60'],
            [ 'name_ar' => 'التاريخ', 'name_en' => 'History', 'icon' => 'fas fa-landmark', 'color' => '#8e44ad'],
            [ 'name_ar' => 'الجغرافيا', 'name_en' => 'Geography', 'icon' => 'fas fa-globe', 'color' => '#16a085'],

            [ 'is_ministry' => false ,'name_ar' => 'التربية الإسلامية', 'name_en' => 'Islamic Education', 'icon' => 'fas fa-mosque', 'color' => '#16a085'],
            [ 'is_ministry' => false ,'name_ar' => 'التربية الوطنية', 'name_en' => 'National Education', 'icon' => 'fas fa-flag', 'color' => '#e67e22'],
            [ 'is_ministry' => false ,'name_ar' => 'الحاسوب', 'name_en' => 'Computer', 'icon' => 'fas fa-laptop', 'color' => '#2ecc71'],
            [ 'is_ministry' => false ,'name_ar' => 'التربية الفنية', 'name_en' => 'Art Education', 'icon' => 'fas fa-palette', 'color' => '#e91e63'],
        ];

        $sortOrder = 1;
        foreach ($subjects as $subject) {
           $createdSubject = Subject::create([
                'name_ar'     => $subject['name_ar'],
                'name_en'     => $subject['name_en'],
                'icon'        => $subject['icon'],
                'color'       => $subject['color'],
                'sort_order'  => $sortOrder,
                // 'is_ministry' => $subject['is_ministry'] ?? true,
                'grade_id'    => $parentId,
                'programm_id' => $programm_id,
            ]);
            CategorySubject::create([
                'is_ministry'  => $subject['is_ministry'] ?? true,
                'category_id'  => $parentId,
                'pivot_level'  => 'grade',
                'subject_id'  => $createdSubject->id,
            ]);
            $sortOrder++;
        }
    }


    /**
     * Create Tawjihi 2008 content
    */
    private function createTawjihi2008Content($parentId)
    {

        $scientificFields = Category::create([
            'name_ar'     => 'حقول علمية',
            'name_en'     => 'Scientific Fields',
            'level'       => 'tawjihi_program_fields',
            'icon'        => 'fas fa-flask',
            'color'       => '#3498db',
            'sort_order'  => 1,
            'parent_id'   => $parentId,
            'type'        => 'class'
        ]);

        // Literary Fields
        $literaryFields = Category::create([
            'name_ar'     => 'حقول أدبية',
            'name_en'     => 'Literary Fields',
            'level'       => 'tawjihi_program_fields',
            'icon'       => 'fas fa-feather-alt',
            'color'      => '#e67e22',
            'sort_order' => 2,
            'parent_id'  => $parentId,
            'type'       => 'class'
        ]);

        // Scientific Field subdivisions
        $this->createScientificFields($scientificFields->id);

        // Literary Field subdivisions
        $this->createLiteraryFields($literaryFields->id);

        $this->createTawjihiFinalGradeSpecificDirectSubjects();
    }

    /**
     * Create Scientific Fields
    */
    private function createScientificFields($parentId)
    {
        $fields = [
            [
                'name_ar' => 'الحقل الطبي',
                'name_en' => 'Medical Field',
                'level'   => 'tawjihi_scientific_fields',
                'icon' => 'fas fa-stethoscope',
                'color' => '#e74c3c',
                'sort_order' => 1,
                'parent_id' => $parentId,
                'type' => 'class'
            ],
            [
                'name_ar' => 'الحقل الهندسي',
                'name_en' => 'Engineering Field',
                'level'   => 'tawjihi_scientific_fields',
                'icon' => 'fas fa-cogs',
                'color' => '#34495e',
                'sort_order' => 2,
                'parent_id' => $parentId,
                'type' => 'class'
            ],
            [
                'name_ar' => 'حقل تكنولوجيا المعلومات',
                'name_en' => 'Information Technology Field',
                'level'   => 'tawjihi_scientific_fields',
                'icon' => 'fas fa-laptop-code',
                'color' => '#2ecc71',
                'sort_order' => 3,
                'parent_id' => $parentId,
                'type' => 'class'
            ]
        ];

        foreach ($fields as $field) {
            $createdField = Category::create($field);
        }
    }

    /**
     * Create Literary Fields
    */
    private function createLiteraryFields($parentId)
    {
        $fields = [
            [
                'name_ar' => 'حقل الأعمال',
                'name_en' => 'Business Field',
                'level'   => 'tawjihi_literary_fields',
                'icon' => 'fas fa-briefcase',
                'color' => '#f39c12',
                'sort_order' => 1,
                'parent_id' => $parentId,
                'type' => 'class'
            ],
            [
                'name_ar' => 'حقل اللغات والعلوم الإجتماعية',
                'name_en' => 'Languages Field',
                'level'   => 'tawjihi_literary_fields',
                'icon' => 'fas fa-language',
                'color' => '#9b59b6',
                'sort_order' => 2,
                'parent_id' => $parentId,
                'type' => 'class'
            ],
            [
                'name_ar' => 'حقل القانون والشريعة',
                'name_en' => 'Law and Sharia Field',
                'level'   => 'tawjihi_literary_fields',
                'icon' => 'fas fa-balance-scale',
                'color' => '#8e44ad',
                'sort_order' => 3,
                'parent_id' => $parentId,
                'type' => 'class'
            ]
        ];

        foreach ($fields as $field) {
            $createdField = Category::create($field);
        }
    }

    /**
     * Create specific subjects based on field and type
    */
    private function createTawjihiFinalGradeSpecificDirectSubjects()
    {
        $programm_id = Category::where('name_en','Tawjihi and Secondary Program')->first()?->id;
        $grade_id    = Category::where('ctg_key','final_year')->first()?->id;
        $scientific_fields_id = Category::where('ctg_key','scientific-fields')->first()?->id;
        $literary_fields_id   = Category::where('ctg_key','literary-fields')->first()?->id;

        $uniqueSubjects = [
            ['name_ar' => 'المهارات الرقمية','name_en' => 'Digital skills','icon' => 'fas fa-keyboard','color' => '#3498db','field_type_id' => $scientific_fields_id, 'is_subject' => 1],
            ['name_ar' => 'الرياضيات','name_en' => 'Mathematics','icon' => 'fas fa-subscript','color' => '#3498db','field_type_id' => $scientific_fields_id, 'is_subject' => 1],
            ['name_ar' => 'الكيمياء','name_en' => 'Chemistry','icon' => 'fas fa-flask','color' => '#e67e22','field_type_id' => $scientific_fields_id, 'is_subject' => 1],
            ['name_ar' => 'العلوم الحياتية','name_en' => 'Biology','icon' => 'fas fa-dna','color' => '#27ae60','field_type_id' => $scientific_fields_id, 'is_subject' => 1],
            ['name_ar' => 'اللغة الإنجليزية (متقدم)','name_en' => 'English Language (advanced)','icon' => 'fas fa-book','color' => '#3498db','field_type_id' => $literary_fields_id, 'is_subject' => 1],
            ['name_ar' => 'الفيزياء','name_en' => 'Physics','icon' => 'fas fa-atom','color' => '#3498db','field_type_id' => $literary_fields_id, 'is_subject' => 1],
            ['name_ar' => 'رياضيات الأعمال','name_en' => 'Business Mathematics','icon' => 'fas fa-subscript','color' => '#e67e22','field_type_id' => $literary_fields_id, 'is_subject' => 1],
            ['name_ar' => 'الثقافة المالية','name_en' => 'Financial Literacy','icon' => 'fas fa-book','color' => '#27ae60','field_type_id' => $literary_fields_id, 'is_subject' => 1],
            ['name_ar' => 'اللغة العربية (تخصص)','name_en' => 'Arabic Language (Specialization)','icon' => 'fas fa-feather-alt','color' => '#e74c3c','field_type_id' => $literary_fields_id, 'is_subject' => 1],
            ['name_ar' => 'التربية الإسلامية (تخصص)','name_en' => 'Islamic Education (Specialization)','icon' => 'fas fa-book','color' => '#3498db','field_type_id' => $literary_fields_id, 'is_subject' => 1],
            ['name_ar' => 'مبحث إختياري','name_en' => 'Optional field','icon' => 'fas fa-dna','color' => '#3498db','field_type_id' => null, 'is_subject' => 1],
            ['name_ar' => 'مبحث علمي','name_en' => 'optional Scientific field','icon' => 'fas fa-dna','color' => '#3498db','field_type_id' => $scientific_fields_id, 'is_subject' => 1],
            ['name_ar' => 'مبحث إنساني','name_en' => 'Humanities','icon' => 'fas fa-language','color' => '#9b59b6','field_type_id' => $literary_fields_id, 'is_subject' => 1],
        ];

        $sortOrder = 1;
        foreach ($uniqueSubjects as $uniqueSubject) {
           $createdSubject = Subject::create([
                'name_ar'     => $uniqueSubject['name_ar'],
                'name_en'     => $uniqueSubject['name_en'],
                'icon'        => $uniqueSubject['icon'],
                'color'       => $uniqueSubject['color'],
                'sort_order'  => $sortOrder,
                'field_type_id'  => $uniqueSubject['field_type_id'],
                'is_subject'  => $uniqueSubject['is_subject'],
                'grade_id'    => $grade_id,
                'programm_id' => $programm_id,
            ]);
            $sortOrder++;
        }

        // related subjects

        $subjects = [];

        // الحقل الطبي
        $medicalCategory = Category::where('name_ar', 'الحقل الطبي')->first();

        $subjects = array_merge($subjects, [
            ['name_en' => 'Digital skills','is_ministry' => false,'is_optional' => false,'category_id' => $medicalCategory->id],
            ['name_en' => 'Mathematics','is_ministry' => false,'is_optional' => false,'category_id' => $medicalCategory->id],
            ['name_en' => 'Optional field','is_ministry' => false,'is_optional' => true,'category_id' => $medicalCategory->id],
            ['name_en' => 'Chemistry','is_ministry' => true,'is_optional' => false,'category_id' => $medicalCategory->id],
            ['name_en' => 'Biology','is_ministry' => true,'is_optional' => false,'category_id' => $medicalCategory->id],
            ['name_en' => 'English Language (advanced)','is_ministry' => true,'is_optional' => false,'category_id' => $medicalCategory->id],
            ['name_en' => 'Optional field','is_ministry' => true,'is_optional' => true,'category_id' => $medicalCategory->id]
        ]);

        // الحقل الهندسي
        $engineeringCategory = Category::where('name_ar', 'الحقل الهندسي')->first();

        $subjects = array_merge($subjects, [
            ['name_en' => 'Digital skills','is_ministry' => false,'is_optional' => false,'category_id' => $engineeringCategory->id],
            ['name_en' => 'Optional field','is_ministry' => false,'is_optional' => true,'category_id' => $engineeringCategory->id],
            ['name_en' => 'Optional field','is_ministry' => false,'is_optional' => true,'category_id' => $engineeringCategory->id],
            ['name_en' => 'Mathematics','is_ministry' => true,'is_optional' => false,'category_id' => $engineeringCategory->id],
            ['name_en' => 'Physics','is_ministry' => true,'is_optional' => false,'category_id' => $engineeringCategory->id],
            ['name_en' => 'optional Scientific field','is_ministry' => true,'is_optional' => true,'category_id' => $engineeringCategory->id],
            ['name_en' => 'Optional field','is_ministry' => true,'is_optional' => true,'category_id' => $engineeringCategory->id]
        ]);

        // حقل تكنولوجيا المعلومات
        $itCategory = Category::where('name_ar', 'حقل تكنولوجيا المعلومات')->first();

        $subjects = array_merge($subjects, [
            ['name_en' => 'Digital skills','is_ministry' => false,'is_optional' => false,'category_id' => $itCategory->id],
            ['name_en' => 'Optional field','is_ministry' => false,'is_optional' => true,'category_id' => $itCategory->id],
            ['name_en' => 'Optional field','is_ministry' => false,'is_optional' => true,'category_id' => $itCategory->id,],
            ['name_en' => 'Mathematics','is_ministry' => true,'is_optional' => false,'category_id' => $itCategory->id],
            ['name_en' => 'optional Scientific field','is_ministry' => true,'is_optional' => true,'category_id' => $itCategory->id],
            ['name_en' => 'optional Scientific field','is_ministry' => true,'is_optional' => true,'category_id' => $itCategory->id],
            ['name_en' => 'Optional field','is_ministry' => true,'is_optional' => true,'category_id' => $itCategory->id]
        ]);

        // حقل الأعمال
        $businessCategory = Category::where('name_ar', 'حقل الأعمال')->first();

        $subjects = array_merge($subjects, [
            ['name_en' => 'Digital skills','is_ministry' => false,'is_optional' => false,'category_id' => $businessCategory->id],
            ['name_en' => 'Optional field','is_ministry' => false,'is_optional' => true,'category_id' => $businessCategory->id],
            ['name_en' => 'Optional field','is_ministry' => false,'is_optional' => true,'category_id' => $businessCategory->id],
            ['name_en' => 'Business Mathematics','is_ministry' => true,'is_optional' => false,'category_id' => $businessCategory->id],
            ['name_en' => 'Financial Literacy','is_ministry' => true,'is_optional' => false,'category_id' => $businessCategory->id],
            ['name_en' => 'English Language (advanced)','is_ministry' => true,'is_optional' => false,'category_id' => $businessCategory->id],
            ['name_en' => 'Optional field','is_ministry' => true,'is_optional' => true,'category_id' => $businessCategory->id]
        ]);

        // حقل اللغات والعلوم الإجتماعية
        $langCategory = Category::where('name_ar', 'حقل اللغات والعلوم الإجتماعية')->first();

        $subjects = array_merge($subjects, [
            ['name_en' => 'Digital skills','is_ministry' => false,'is_optional' => false,'category_id' => $langCategory->id],
            ['name_en' => 'Optional field','is_ministry' => false,'is_optional' => true,'category_id' => $langCategory->id],
            ['name_en' => 'Optional field','is_ministry' => false,'is_optional' => true,'category_id' => $langCategory->id],
            ['name_en' => 'Arabic Language (Specialization)','is_ministry' => true,'is_optional' => false,'category_id' => $langCategory->id],
            ['name_en' => 'Islamic Education (Specialization)','is_ministry' => true,'is_optional' => false,'category_id' => $langCategory->id],
            ['name_en' => 'Humanities','is_ministry' => true,'is_optional' => true,'category_id' => $langCategory->id],
            ['name_en' => 'Optional field','is_ministry' => true,'is_optional' => true,'category_id' => $langCategory->id]
        ]);

        // حقل القانون والشريعة
        $lawCategory = Category::where('name_ar', 'حقل القانون والشريعة')->first();

        $subjects = array_merge($subjects, [
            ['name_en' => 'Digital skills','is_ministry' => false,'is_optional' => false,'category_id'=> $lawCategory->id],
            ['name_en' => 'Optional field','is_ministry' => false,'is_optional' => true,'category_id' => $lawCategory->id],
            ['name_en' => 'Optional field','is_ministry' => false,'is_optional' => true,'category_id' => $lawCategory->id],
            ['name_en' => 'Arabic Language (Specialization)','is_ministry' => true,'is_optional' => false,'category_id' => $lawCategory->id],
            ['name_en' => 'English Language (advanced)','is_ministry' => true,'is_optional' => false,'category_id' => $lawCategory->id],
            ['name_en' => 'Humanities','is_ministry' => true,'is_optional' => true,'category_id'  => $lawCategory->id],
            ['name_en' => 'Optional field','is_ministry' => true,'is_optional' => true,'category_id'  => $lawCategory->id]
        ]);

        foreach ($subjects as $key => $subject) {
            $subject['subject_id']  = Subject::where('name_en',$subject['name_en'])->where('grade_id',$grade_id)->first()?->id;
            $subject['pivot_level'] = 'field';
            unset($subject['name_en']);
            CategorySubject::create($subject);
        }
    }


    /**
     * Create Elementary Program structure
    */
    private function createElementaryProgram($parentId)
    {
        $grades = [
            'الصف الأول'   => 'First Grade',
            'الصف الثاني' => 'Second Grade',
            'الصف الثالث' => 'Third Grade',
            'الصف الرابع' => 'Fourth Grade',
            'الصف الخامس' => 'Fifth Grade',
            'الصف السادس' => 'Sixth Grade',
            'الصف السابع' => 'Seventh Grade',
            'الصف الثامن' => 'Eighth Grade',
            'الصف التاسع' => 'Ninth Grade',
            'الصف العاشر' => 'Tenth Grade'
        ];

        $sortOrder = 1;
        foreach ($grades as $gradeAr => $gradeEn) {
            $grade = Category::create([
                'name_ar'    => $gradeAr,
                'name_en'    => $gradeEn,
                'icon'       => 'fas fa-book-reader',
                'color'      => '#3498db',
                'sort_order' => $sortOrder,
                'parent_id'  => $parentId,
                'type'       => 'class',
                'level'      => 'elementray_grade'
            ]);

            // Create semesters for each grade
            $this->createSemesters($grade->id);
            $sortOrder++;
        }
    }

    /**
     * Create semesters for a grade
    */
    private function createSemesters($gradeId)
    {
        $semesters = [
            [
                'name_ar' => 'الفصل الأول',
                'name_en' => 'First Semester',
                'icon' => 'fas fa-calendar-alt',
                'color' => '#2ecc71',
                'sort_order' => 1,
                'parent_id' => $gradeId,
                'type' => 'class',
                'level' => 'semester',
            ],
            [
                'name_ar' => 'الفصل الثاني',
                'name_en' => 'Second Semester',
                'icon' => 'fas fa-calendar-alt',
                'color' => '#27ae60',
                'sort_order' => 2,
                'parent_id' => $gradeId,
                'type' => 'class',
                'level' => 'semester',
            ]
        ];

        foreach ($semesters as $semester) {
            $createdSemester = Category::create($semester);
            // Create subjects for each semester
            $this->createElementarySubjects($createdSemester->id,$gradeId);
        }
    }

    /**
     * Create subjects for elementary grades
     */
    private function createElementarySubjects($semesterId,$gradeId)
    {
        $subjects = [
            [
                'name_ar' => 'اللغة العربية',
                'name_en' => 'Arabic Language',
                'icon' => 'fas fa-font',
                'color' => '#e74c3c',
                'sort_order' => 1
            ],
            [
                'name_ar' => 'اللغة الإنجليزية',
                'name_en' => 'English Language',
                'icon' => 'fas fa-language',
                'color' => '#3498db',
                'sort_order' => 2
            ],
            [
                'name_ar' => 'الرياضيات',
                'name_en' => 'Mathematics',
                'icon' => 'fas fa-calculator',
                'color' => '#f39c12',
                'sort_order' => 3
            ],
            [
                'name_ar' => 'العلوم',
                'name_en' => 'Sciences',
                'icon' => 'fas fa-microscope',
                'color' => '#27ae60',
                'sort_order' => 4
            ],
            [
                'name_ar' => 'التربية الإسلامية',
                'name_en' => 'Islamic Education',
                'icon' => 'fas fa-mosque',
                'color' => '#16a085',
                'sort_order' => 5
            ],
            [
                'name_ar' => 'الدراسات الاجتماعية',
                'name_en' => 'Social Studies',
                'icon' => 'fas fa-globe-americas',
                'color' => '#8e44ad',
                'sort_order' => 6
            ]
        ];

        $programm_id = Category::where('name_en','Elementary Grades Program')->first()?->id;
        foreach ($subjects as $subject) {
            $subject['semester_id'] = $semesterId;
            $subject['grade_id']    = $gradeId;
            $subject['programm_id'] = $programm_id;

            $createdSubject = Subject::create($subject);
            CategorySubject::create([
                'subject_id' => $createdSubject->id,
                'category_id' => $semesterId,
                'pivot_level'  => 'semester',
            ]);
        }

    }

    /**
     * Create subjects for international programs
    */
    private function createInternationalSubjects($programmId)
    {
        $subjects = [
            ['name_ar' => 'اللغة الإنجليزية', 'name_en' => 'English Language Arts', 'icon' => 'fas fa-language', 'color' => '#3498db'],
            ['name_ar' => 'الرياضيات', 'name_en' => 'Mathematics', 'icon' => 'fas fa-calculator', 'color' => '#f39c12'],
            ['name_ar' => 'العلوم', 'name_en' => 'Science', 'icon' => 'fas fa-microscope', 'color' => '#27ae60'],
            ['name_ar' => 'الدراسات الاجتماعية', 'name_en' => 'Social Studies', 'icon' => 'fas fa-globe-americas', 'color' => '#8e44ad'],
            ['name_ar' => 'الفنون', 'name_en' => 'Arts', 'icon' => 'fas fa-palette', 'color' => '#e91e63'],
            ['name_ar' => 'التاريخ', 'name_en' => 'History', 'icon' => 'fas fa-landmark', 'color' => '#8e44ad'],
            ['name_ar' => 'الجغرافيا', 'name_en' => 'Geography', 'icon' => 'fas fa-globe', 'color' => '#16a085'],
        ];

        $sortOrder = 1;
        foreach ($subjects as $subject) {
            $createdSubject = Subject::create([
                'name_ar' => $subject['name_ar'],
                'name_en' => $subject['name_en'],
                'icon' => $subject['icon'],
                'color' => $subject['color'],
                'sort_order' => $sortOrder,
                'grade_id' => $programmId,
                'programm_id' => $programmId,
            ]);
            $sortOrder++;
            CategorySubject::create([
                'subject_id' => $createdSubject->id,
                'category_id' => $programmId,
                'pivot_level'  => 'programm',
            ]);
        }
    }

    /**
     * Create subjects for university faculties
     */
    private function createFacultySubjects($programmId)
    {
        $subjects = [
            ['name_ar' => 'التشريح', 'name_en' => 'Anatomy', 'icon' => 'fas fa-bone', 'color' => '#e74c3c'],
            ['name_ar' => 'علم وظائف الأعضاء', 'name_en' => 'Physiology', 'icon' => 'fas fa-heartbeat', 'color' => '#27ae60'],
            ['name_ar' => 'الكيمياء الحيوية', 'name_en' => 'Biochemistry', 'icon' => 'fas fa-flask', 'color' => '#e67e22'],
            ['name_ar' => 'الأمراض', 'name_en' => 'Pathology', 'icon' => 'fas fa-virus', 'color' => '#8e44ad'],
            ['name_ar' => 'الرياضيات الهندسية', 'name_en' => 'Engineering Mathematics', 'icon' => 'fas fa-calculator', 'color' => '#f39c12'],
            ['name_ar' => 'الفيزياء الهندسية', 'name_en' => 'Engineering Physics', 'icon' => 'fas fa-atom', 'color' => '#3498db'],
            ['name_ar' => 'المواد الهندسية', 'name_en' => 'Engineering Materials', 'icon' => 'fas fa-cube', 'color' => '#34495e'],
            ['name_ar' => 'التصميم الهندسي', 'name_en' => 'Engineering Design', 'icon' => 'fas fa-drafting-compass', 'color' => '#2ecc71'],
            ['name_ar' => 'الأدب العربي', 'name_en' => 'Arabic Literature', 'icon' => 'fas fa-feather-alt', 'color' => '#e74c3c'],
            ['name_ar' => 'اللسانيات', 'name_en' => 'Linguistics', 'icon' => 'fas fa-language', 'color' => '#9b59b6'],
            ['name_ar' => 'التاريخ', 'name_en' => 'History', 'icon' => 'fas fa-landmark', 'color' => '#8e44ad'],
            ['name_ar' => 'الفلسفة', 'name_en' => 'Philosophy', 'icon' => 'fas fa-brain', 'color' => '#16a085'],
            ['name_ar' => 'إدارة الأعمال', 'name_en' => 'Business Administration', 'icon' => 'fas fa-briefcase', 'color' => '#f39c12'],
            ['name_ar' => 'المحاسبة', 'name_en' => 'Accounting', 'icon' => 'fas fa-calculator', 'color' => '#27ae60'],
            ['name_ar' => 'التسويق', 'name_en' => 'Marketing', 'icon' => 'fas fa-bullhorn', 'color' => '#e74c3c'],
            ['name_ar' => 'الاقتصاد', 'name_en' => 'Economics', 'icon' => 'fas fa-chart-line', 'color' => '#3498db'],
            ['name_ar' => 'البرمجة', 'name_en' => 'Programming', 'icon' => 'fas fa-code', 'color' => '#2ecc71'],
            ['name_ar' => 'قواعد البيانات', 'name_en' => 'Database', 'icon' => 'fas fa-database', 'color' => '#3498db'],
            ['name_ar' => 'الشبكات', 'name_en' => 'Networks', 'icon' => 'fas fa-network-wired', 'color' => '#f39c12'],
            ['name_ar' => 'أمن المعلومات', 'name_en' => 'Information Security', 'icon' => 'fas fa-shield-alt', 'color' => '#e74c3c'],
        ];

        $sortOrder = 1;
        foreach ($subjects as $subject) {
            $createdSubject = Subject::create([
                'name_ar'     => $subject['name_ar'],
                'name_en'     => $subject['name_en'],
                'icon'        => $subject['icon'],
                'color'       => $subject['color'],
                'sort_order'  => $sortOrder,
                'grade_id'    => $programmId,
                'programm_id' => $programmId,
            ]);
            $sortOrder++;
            CategorySubject::create([
                'subject_id'   => $createdSubject->id,
                'category_id'  => $programmId,
                'pivot_level'  => 'programm',
            ]);
        }
    }

    /**
     * Create vocational subjects
     */
    private function createVocationalSubjects($parentId)
    {
        $subjects = [
            ['name_ar' => 'الكهرباء', 'name_en' => 'Electrical', 'icon' => 'fas fa-bolt', 'color' => '#f39c12'],
            ['name_ar' => 'السباكة', 'name_en' => 'Plumbing', 'icon' => 'fas fa-wrench', 'color' => '#3498db'],
            ['name_ar' => 'النجارة', 'name_en' => 'Carpentry', 'icon' => 'fas fa-hammer', 'color' => '#8e44ad'],
            ['name_ar' => 'الميكانيكا', 'name_en' => 'Mechanics', 'icon' => 'fas fa-cog', 'color' => '#34495e'],
            ['name_ar' => 'الحاسوب', 'name_en' => 'Computer', 'icon' => 'fas fa-laptop', 'color' => '#2ecc71'],
        ];

        $sortOrder = 1;
        foreach ($subjects as $subject) {
            Category::create([
                'name_ar' => $subject['name_ar'],
                'name_en' => $subject['name_en'],
                'icon' => $subject['icon'],
                'color' => $subject['color'],
                'sort_order' => $sortOrder,
                'parent_id' => $parentId,
            ]);
            $sortOrder++;
        }
    }
}
