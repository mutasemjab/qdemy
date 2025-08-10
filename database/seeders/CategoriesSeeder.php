<?php

namespace Database\Seeders;

use App\Models\Category;
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
                'icon' => 'fas fa-graduation-cap',
                'color' => '#e74c3c',
                'sort_order' => 1,
                'type' => 'major'
            ],
            [
                'name_ar' => 'برنامج الصفوف الأساسية',
                'name_en' => 'Elementary Grades Program',
                'icon' => 'fas fa-school',
                'color' => '#3498db',
                'sort_order' => 2,
                'type' => 'major'
            ],
            [
                'name_ar' => 'برنامج الجامعات والكليات',
                'name_en' => 'Universities and Colleges Program',
                'icon' => 'fas fa-university',
                'color' => '#9b59b6',
                'sort_order' => 3,
                'type' => 'major'
            ],
            [
                'name_ar' => 'البرنامج الدولي',
                'name_en' => 'International Program',
                'icon' => 'fas fa-globe',
                'color' => '#f39c12',
                'sort_order' => 4,
                'type' => 'major'
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
            case 'برنامج التوجيهي والثانوي':
                $this->createTawjihiProgram($mainProgram->id);
                break;
                
            case 'برنامج الصفوف الأساسية':
                $this->createElementaryProgram($mainProgram->id);
                break;
                
            case 'البرنامج الدولي':
                $this->createInternationalProgram($mainProgram->id);
                break;
                
            // Universities program has no subcategories
            case 'برنامج الجامعات والكليات':
                // Add university subjects directly
                $this->createUniversitySubjects($mainProgram->id);
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
                'icon' => 'fas fa-book',
                'color' => '#e74c3c',
                'sort_order' => 1,
                'parent_id' => $parentId,
                'type' => 'class'
            ],
            [
                'name_ar' => 'توجيهي 2008',
                'name_en' => 'Tawjihi 2008',
                'icon' => 'fas fa-book-open',
                'color' => '#c0392b',
                'sort_order' => 2,
                'parent_id' => $parentId,
                'type' => 'class'
            ],
            [
                'name_ar' => 'النظام المهني',
                'name_en' => 'Vocational System',
                'icon' => 'fas fa-tools',
                'color' => '#8e44ad',
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
        $subjects = [
            [
                'name_ar' => 'مواد وزارية',
                'name_en' => 'Ministry Subjects',
                'icon' => 'fas fa-landmark',
                'color' => '#27ae60',
                'sort_order' => 1,
                'parent_id' => $parentId,
                'type' => 'class'
            ],
            [
                'name_ar' => 'مواد مدرسية',
                'name_en' => 'School Subjects',
                'icon' => 'fas fa-chalkboard-teacher',
                'color' => '#16a085',
                'sort_order' => 2,
                'parent_id' => $parentId,
                'type' => 'class'
            ]
        ];

        foreach ($subjects as $subject) {
            $createdSubject = Category::create($subject);
            // Add actual subjects for each category
            $this->createTawjihi2009Subjects($createdSubject->id, $subject['name_ar']);
        }
    }

    /**
     * Create Tawjihi 2009 actual subjects
     */
    private function createTawjihi2009Subjects($parentId, $type)
    {
        if ($type === 'مواد وزارية') {
            $subjects = [
                ['name_ar' => 'اللغة العربية', 'name_en' => 'Arabic Language', 'icon' => 'fas fa-font', 'color' => '#e74c3c'],
                ['name_ar' => 'اللغة الإنجليزية', 'name_en' => 'English Language', 'icon' => 'fas fa-language', 'color' => '#3498db'],
                ['name_ar' => 'الرياضيات', 'name_en' => 'Mathematics', 'icon' => 'fas fa-calculator', 'color' => '#f39c12'],
                ['name_ar' => 'العلوم', 'name_en' => 'Sciences', 'icon' => 'fas fa-microscope', 'color' => '#27ae60'],
                ['name_ar' => 'التاريخ', 'name_en' => 'History', 'icon' => 'fas fa-landmark', 'color' => '#8e44ad'],
                ['name_ar' => 'الجغرافيا', 'name_en' => 'Geography', 'icon' => 'fas fa-globe', 'color' => '#16a085'],
            ];
        } else {
            $subjects = [
                ['name_ar' => 'التربية الإسلامية', 'name_en' => 'Islamic Education', 'icon' => 'fas fa-mosque', 'color' => '#16a085'],
                ['name_ar' => 'التربية الوطنية', 'name_en' => 'National Education', 'icon' => 'fas fa-flag', 'color' => '#e67e22'],
                ['name_ar' => 'الحاسوب', 'name_en' => 'Computer', 'icon' => 'fas fa-laptop', 'color' => '#2ecc71'],
                ['name_ar' => 'التربية الفنية', 'name_en' => 'Art Education', 'icon' => 'fas fa-palette', 'color' => '#e91e63'],
            ];
        }

        $sortOrder = 1;
        foreach ($subjects as $subject) {
            Category::create([
                'name_ar' => $subject['name_ar'],
                'name_en' => $subject['name_en'],
                'icon' => $subject['icon'],
                'color' => $subject['color'],
                'sort_order' => $sortOrder,
                'parent_id' => $parentId,
                'type' => 'lesson'  // These are the actual lessons
            ]);
            $sortOrder++;
        }
    }

    /**
     * Create Tawjihi 2008 content
     */
    private function createTawjihi2008Content($parentId)
    {
        // Scientific Fields
        $scientificFields = Category::create([
            'name_ar' => 'حقول علمية',
            'name_en' => 'Scientific Fields',
            'icon' => 'fas fa-flask',
            'color' => '#3498db',
            'sort_order' => 1,
            'parent_id' => $parentId,
            'type' => 'class'
        ]);

        // Literary Fields
        $literaryFields = Category::create([
            'name_ar' => 'حقول أدبية',
            'name_en' => 'Literary Fields',
            'icon' => 'fas fa-feather-alt',
            'color' => '#e67e22',
            'sort_order' => 2,
            'parent_id' => $parentId,
            'type' => 'class'
        ]);

        // Scientific Field subdivisions
        $this->createScientificFields($scientificFields->id);
        
        // Literary Field subdivisions
        $this->createLiteraryFields($literaryFields->id);
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
                'icon' => 'fas fa-stethoscope',
                'color' => '#e74c3c',
                'sort_order' => 1,
                'parent_id' => $parentId,
                'type' => 'class'
            ],
            [
                'name_ar' => 'الحقل الهندسي',
                'name_en' => 'Engineering Field',
                'icon' => 'fas fa-cogs',
                'color' => '#34495e',
                'sort_order' => 2,
                'parent_id' => $parentId,
                'type' => 'class'
            ],
            [
                'name_ar' => 'حقل تكنولوجيا المعلومات',
                'name_en' => 'Information Technology Field',
                'icon' => 'fas fa-laptop-code',
                'color' => '#2ecc71',
                'sort_order' => 3,
                'parent_id' => $parentId,
                'type' => 'class'
            ]
        ];

        foreach ($fields as $field) {
            $createdField = Category::create($field);
            $this->createFieldSubjects($createdField->id, $field['name_ar']);
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
                'icon' => 'fas fa-briefcase',
                'color' => '#f39c12',
                'sort_order' => 1,
                'parent_id' => $parentId,
                'type' => 'class'
            ],
            [
                'name_ar' => 'حقل اللغات',
                'name_en' => 'Languages Field',
                'icon' => 'fas fa-language',
                'color' => '#9b59b6',
                'sort_order' => 2,
                'parent_id' => $parentId,
                'type' => 'class'
            ],
            [
                'name_ar' => 'حقل القانون والشريعة',
                'name_en' => 'Law and Sharia Field',
                'icon' => 'fas fa-balance-scale',
                'color' => '#8e44ad',
                'sort_order' => 3,
                'parent_id' => $parentId,
                'type' => 'class'
            ]
        ];

        foreach ($fields as $field) {
            $createdField = Category::create($field);
            $this->createFieldSubjects($createdField->id, $field['name_ar']);
        }
    }

    /**
     * Create subjects for each field (Medical, Engineering, etc.)
     */
    private function createFieldSubjects($parentId, $fieldName)
    {
        $subjectTypes = [
            [
                'name_ar' => 'مواد إجبارية',
                'name_en' => 'Mandatory Subjects',
                'icon' => 'fas fa-exclamation-circle',
                'color' => '#e74c3c',
                'sort_order' => 1,
                'parent_id' => $parentId,
                'type' => 'class'
            ],
            [
                'name_ar' => 'مواد اختيارية',
                'name_en' => 'Optional Subjects',
                'icon' => 'fas fa-check-circle',
                'color' => '#27ae60',
                'sort_order' => 2,
                'parent_id' => $parentId,
                'type' => 'class'
            ]
        ];

        foreach ($subjectTypes as $type) {
            $createdType = Category::create($type);
            $this->createSpecificSubjects($createdType->id, $fieldName, $type['name_ar']);
        }
    }

    /**
     * Create specific subjects based on field and type
     */
    private function createSpecificSubjects($parentId, $fieldName, $subjectType)
    {
        $subjects = [];
        
        // Common mandatory subjects for all fields
        if ($subjectType === 'مواد إجبارية') {
            $subjects = [
                ['name_ar' => 'اللغة العربية', 'name_en' => 'Arabic Language', 'icon' => 'fas fa-font', 'color' => '#e74c3c'],
                ['name_ar' => 'اللغة الإنجليزية', 'name_en' => 'English Language', 'icon' => 'fas fa-language', 'color' => '#3498db'],
                ['name_ar' => 'الرياضيات', 'name_en' => 'Mathematics', 'icon' => 'fas fa-calculator', 'color' => '#f39c12'],
                ['name_ar' => 'التربية الإسلامية', 'name_en' => 'Islamic Education', 'icon' => 'fas fa-mosque', 'color' => '#16a085'],
            ];

            // Add field-specific mandatory subjects
            switch ($fieldName) {
                case 'الحقل الطبي':
                    $subjects = array_merge($subjects, [
                        ['name_ar' => 'الأحياء', 'name_en' => 'Biology', 'icon' => 'fas fa-dna', 'color' => '#27ae60'],
                        ['name_ar' => 'الكيمياء', 'name_en' => 'Chemistry', 'icon' => 'fas fa-flask', 'color' => '#e67e22'],
                        ['name_ar' => 'الفيزياء', 'name_en' => 'Physics', 'icon' => 'fas fa-atom', 'color' => '#3498db'],
                    ]);
                    break;
                    
                case 'الحقل الهندسي':
                    $subjects = array_merge($subjects, [
                        ['name_ar' => 'الفيزياء', 'name_en' => 'Physics', 'icon' => 'fas fa-atom', 'color' => '#3498db'],
                        ['name_ar' => 'الكيمياء', 'name_en' => 'Chemistry', 'icon' => 'fas fa-flask', 'color' => '#e67e22'],
                        ['name_ar' => 'الرسم الهندسي', 'name_en' => 'Engineering Drawing', 'icon' => 'fas fa-drafting-compass', 'color' => '#34495e'],
                    ]);
                    break;
                    
                case 'حقل تكنولوجيا المعلومات':
                    $subjects = array_merge($subjects, [
                        ['name_ar' => 'علوم الحاسوب', 'name_en' => 'Computer Science', 'icon' => 'fas fa-laptop-code', 'color' => '#2ecc71'],
                        ['name_ar' => 'الفيزياء', 'name_en' => 'Physics', 'icon' => 'fas fa-atom', 'color' => '#3498db'],
                        ['name_ar' => 'الإحصاء', 'name_en' => 'Statistics', 'icon' => 'fas fa-chart-bar', 'color' => '#9b59b6'],
                    ]);
                    break;
                    
                case 'حقل الأعمال':
                    $subjects = array_merge($subjects, [
                        ['name_ar' => 'الاقتصاد', 'name_en' => 'Economics', 'icon' => 'fas fa-chart-line', 'color' => '#f39c12'],
                        ['name_ar' => 'إدارة الأعمال', 'name_en' => 'Business Administration', 'icon' => 'fas fa-briefcase', 'color' => '#34495e'],
                        ['name_ar' => 'المحاسبة', 'name_en' => 'Accounting', 'icon' => 'fas fa-calculator', 'color' => '#27ae60'],
                    ]);
                    break;
                    
                case 'حقل اللغات':
                    $subjects = array_merge($subjects, [
                        ['name_ar' => 'الأدب العربي', 'name_en' => 'Arabic Literature', 'icon' => 'fas fa-feather-alt', 'color' => '#e74c3c'],
                        ['name_ar' => 'الأدب الإنجليزي', 'name_en' => 'English Literature', 'icon' => 'fas fa-book', 'color' => '#3498db'],
                        ['name_ar' => 'اللسانيات', 'name_en' => 'Linguistics', 'icon' => 'fas fa-language', 'color' => '#9b59b6'],
                    ]);
                    break;
                    
                case 'حقل القانون والشريعة':
                    $subjects = array_merge($subjects, [
                        ['name_ar' => 'الفقه الإسلامي', 'name_en' => 'Islamic Jurisprudence', 'icon' => 'fas fa-balance-scale', 'color' => '#16a085'],
                        ['name_ar' => 'القانون المدني', 'name_en' => 'Civil Law', 'icon' => 'fas fa-gavel', 'color' => '#8e44ad'],
                        ['name_ar' => 'أصول الفقه', 'name_en' => 'Principles of Jurisprudence', 'icon' => 'fas fa-scroll', 'color' => '#e67e22'],
                    ]);
                    break;
            }
        } else {
            // Optional subjects - more general
            $subjects = [
                ['name_ar' => 'التاريخ', 'name_en' => 'History', 'icon' => 'fas fa-landmark', 'color' => '#8e44ad'],
                ['name_ar' => 'الجغرافيا', 'name_en' => 'Geography', 'icon' => 'fas fa-globe', 'color' => '#16a085'],
                ['name_ar' => 'التربية الفنية', 'name_en' => 'Art Education', 'icon' => 'fas fa-palette', 'color' => '#e91e63'],
                ['name_ar' => 'التربية الرياضية', 'name_en' => 'Physical Education', 'icon' => 'fas fa-running', 'color' => '#f39c12'],
            ];
        }

        $sortOrder = 1;
        foreach ($subjects as $subject) {
            Category::create([
                'name_ar' => $subject['name_ar'],
                'name_en' => $subject['name_en'],
                'icon' => $subject['icon'],
                'color' => $subject['color'],
                'sort_order' => $sortOrder,
                'parent_id' => $parentId,
                'type' => 'lesson'  // These are the actual lessons
            ]);
            $sortOrder++;
        }
    }

    /**
     * Create Elementary Program structure
     */
    private function createElementaryProgram($parentId)
    {
        $grades = [
            'الصف الأول' => 'First Grade',
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
                'name_ar' => $gradeAr,
                'name_en' => $gradeEn,
                'icon' => 'fas fa-book-reader',
                'color' => '#3498db',
                'sort_order' => $sortOrder,
                'parent_id' => $parentId,
                'type' => 'class'
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
                'type' => 'class'
            ],
            [
                'name_ar' => 'الفصل الثاني',
                'name_en' => 'Second Semester',
                'icon' => 'fas fa-calendar-alt',
                'color' => '#27ae60',
                'sort_order' => 2,
                'parent_id' => $gradeId,
                'type' => 'class'
            ]
        ];

        foreach ($semesters as $semester) {
            $createdSemester = Category::create($semester);
            
            // Create subjects for each semester
            $this->createElementarySubjects($createdSemester->id);
        }
    }

    /**
     * Create subjects for elementary grades
     */
    private function createElementarySubjects($semesterId)
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

        foreach ($subjects as $subject) {
            $subject['parent_id'] = $semesterId;
            $subject['type'] = 'lesson';  // These are the actual lessons
            Category::create($subject);
        }
    }

    /**
     * Create International Program structure
     */
    private function createInternationalProgram($parentId)
    {
        $programs = [
            [
                'name_ar' => 'البرنامج الأمريكي',
                'name_en' => 'American Program',
                'icon' => 'fas fa-flag-usa',
                'color' => '#e74c3c',
                'sort_order' => 1,
                'parent_id' => $parentId,
                'type' => 'class'
            ],
            [
                'name_ar' => 'البرنامج البريطاني',
                'name_en' => 'British Program',
                'icon' => 'fas fa-crown',
                'color' => '#3498db',
                'sort_order' => 2,
                'parent_id' => $parentId,
                'type' => 'class'
            ]
        ];

        foreach ($programs as $program) {
            $createdProgram = Category::create($program);
            $this->createInternationalSubjects($createdProgram->id, $program['name_ar']);
        }
    }

    /**
     * Create subjects for international programs
     */
    private function createInternationalSubjects($parentId, $programName)
    {
        if ($programName === 'البرنامج الأمريكي') {
            $subjects = [
                ['name_ar' => 'اللغة الإنجليزية', 'name_en' => 'English Language Arts', 'icon' => 'fas fa-language', 'color' => '#3498db'],
                ['name_ar' => 'الرياضيات', 'name_en' => 'Mathematics', 'icon' => 'fas fa-calculator', 'color' => '#f39c12'],
                ['name_ar' => 'العلوم', 'name_en' => 'Science', 'icon' => 'fas fa-microscope', 'color' => '#27ae60'],
                ['name_ar' => 'الدراسات الاجتماعية', 'name_en' => 'Social Studies', 'icon' => 'fas fa-globe-americas', 'color' => '#8e44ad'],
                ['name_ar' => 'الفنون', 'name_en' => 'Arts', 'icon' => 'fas fa-palette', 'color' => '#e91e63'],
            ];
        } else {
            $subjects = [
                ['name_ar' => 'اللغة الإنجليزية', 'name_en' => 'English', 'icon' => 'fas fa-language', 'color' => '#3498db'],
                ['name_ar' => 'الرياضيات', 'name_en' => 'Mathematics', 'icon' => 'fas fa-calculator', 'color' => '#f39c12'],
                ['name_ar' => 'العلوم', 'name_en' => 'Science', 'icon' => 'fas fa-microscope', 'color' => '#27ae60'],
                ['name_ar' => 'التاريخ', 'name_en' => 'History', 'icon' => 'fas fa-landmark', 'color' => '#8e44ad'],
                ['name_ar' => 'الجغرافيا', 'name_en' => 'Geography', 'icon' => 'fas fa-globe', 'color' => '#16a085'],
            ];
        }

        $sortOrder = 1;
        foreach ($subjects as $subject) {
            Category::create([
                'name_ar' => $subject['name_ar'],
                'name_en' => $subject['name_en'],
                'icon' => $subject['icon'],
                'color' => $subject['color'],
                'sort_order' => $sortOrder,
                'parent_id' => $parentId,
                'type' => 'lesson'  // These are the actual lessons
            ]);
            $sortOrder++;
        }
    }

    /**
     * Create university subjects
     */
    private function createUniversitySubjects($parentId)
    {
        $faculties = [
            [
                'name_ar' => 'كلية الطب',
                'name_en' => 'Faculty of Medicine',
                'icon' => 'fas fa-stethoscope',
                'color' => '#e74c3c',
                'sort_order' => 1,
                'type' => 'class'
            ],
            [
                'name_ar' => 'كلية الهندسة',
                'name_en' => 'Faculty of Engineering',
                'icon' => 'fas fa-cogs',
                'color' => '#34495e',
                'sort_order' => 2,
                'type' => 'class'
            ],
            [
                'name_ar' => 'كلية الآداب',
                'name_en' => 'Faculty of Arts',
                'icon' => 'fas fa-feather-alt',
                'color' => '#9b59b6',
                'sort_order' => 3,
                'type' => 'class'
            ],
            [
                'name_ar' => 'كلية الأعمال',
                'name_en' => 'Faculty of Business',
                'icon' => 'fas fa-briefcase',
                'color' => '#f39c12',
                'sort_order' => 4,
                'type' => 'class'
            ],
            [
                'name_ar' => 'كلية الحاسوب',
                'name_en' => 'Faculty of Computer Science',
                'icon' => 'fas fa-laptop-code',
                'color' => '#2ecc71',
                'sort_order' => 5,
                'type' => 'class'
            ]
        ];

        foreach ($faculties as $faculty) {
            $createdFaculty = Category::create([
                'name_ar' => $faculty['name_ar'],
                'name_en' => $faculty['name_en'],
                'icon' => $faculty['icon'],
                'color' => $faculty['color'],
                'sort_order' => $faculty['sort_order'],
                'parent_id' => $parentId,
                'type' => $faculty['type']
            ]);

            $this->createFacultySubjects($createdFaculty->id, $faculty['name_ar']);
        }
    }

    /**
     * Create subjects for university faculties
     */
    private function createFacultySubjects($parentId, $facultyName)
    {
        $subjects = [];
        
        switch ($facultyName) {
            case 'كلية الطب':
                $subjects = [
                    ['name_ar' => 'التشريح', 'name_en' => 'Anatomy', 'icon' => 'fas fa-bone', 'color' => '#e74c3c'],
                    ['name_ar' => 'علم وظائف الأعضاء', 'name_en' => 'Physiology', 'icon' => 'fas fa-heartbeat', 'color' => '#27ae60'],
                    ['name_ar' => 'الكيمياء الحيوية', 'name_en' => 'Biochemistry', 'icon' => 'fas fa-flask', 'color' => '#e67e22'],
                    ['name_ar' => 'الأمراض', 'name_en' => 'Pathology', 'icon' => 'fas fa-virus', 'color' => '#8e44ad'],
                ];
                break;
                
            case 'كلية الهندسة':
                $subjects = [
                    ['name_ar' => 'الرياضيات الهندسية', 'name_en' => 'Engineering Mathematics', 'icon' => 'fas fa-calculator', 'color' => '#f39c12'],
                    ['name_ar' => 'الفيزياء الهندسية', 'name_en' => 'Engineering Physics', 'icon' => 'fas fa-atom', 'color' => '#3498db'],
                    ['name_ar' => 'المواد الهندسية', 'name_en' => 'Engineering Materials', 'icon' => 'fas fa-cube', 'color' => '#34495e'],
                    ['name_ar' => 'التصميم الهندسي', 'name_en' => 'Engineering Design', 'icon' => 'fas fa-drafting-compass', 'color' => '#2ecc71'],
                ];
                break;
                
            case 'كلية الآداب':
                $subjects = [
                    ['name_ar' => 'الأدب العربي', 'name_en' => 'Arabic Literature', 'icon' => 'fas fa-feather-alt', 'color' => '#e74c3c'],
                    ['name_ar' => 'اللسانيات', 'name_en' => 'Linguistics', 'icon' => 'fas fa-language', 'color' => '#9b59b6'],
                    ['name_ar' => 'التاريخ', 'name_en' => 'History', 'icon' => 'fas fa-landmark', 'color' => '#8e44ad'],
                    ['name_ar' => 'الفلسفة', 'name_en' => 'Philosophy', 'icon' => 'fas fa-brain', 'color' => '#16a085'],
                ];
                break;
                
            case 'كلية الأعمال':
                $subjects = [
                    ['name_ar' => 'إدارة الأعمال', 'name_en' => 'Business Administration', 'icon' => 'fas fa-briefcase', 'color' => '#f39c12'],
                    ['name_ar' => 'المحاسبة', 'name_en' => 'Accounting', 'icon' => 'fas fa-calculator', 'color' => '#27ae60'],
                    ['name_ar' => 'التسويق', 'name_en' => 'Marketing', 'icon' => 'fas fa-bullhorn', 'color' => '#e74c3c'],
                    ['name_ar' => 'الاقتصاد', 'name_en' => 'Economics', 'icon' => 'fas fa-chart-line', 'color' => '#3498db'],
                ];
                break;
                
            case 'كلية الحاسوب':
                $subjects = [
                    ['name_ar' => 'البرمجة', 'name_en' => 'Programming', 'icon' => 'fas fa-code', 'color' => '#2ecc71'],
                    ['name_ar' => 'قواعد البيانات', 'name_en' => 'Database', 'icon' => 'fas fa-database', 'color' => '#3498db'],
                    ['name_ar' => 'الشبكات', 'name_en' => 'Networks', 'icon' => 'fas fa-network-wired', 'color' => '#f39c12'],
                    ['name_ar' => 'أمن المعلومات', 'name_en' => 'Information Security', 'icon' => 'fas fa-shield-alt', 'color' => '#e74c3c'],
                ];
                break;
        }

        $sortOrder = 1;
        foreach ($subjects as $subject) {
            Category::create([
                'name_ar' => $subject['name_ar'],
                'name_en' => $subject['name_en'],
                'icon' => $subject['icon'],
                'color' => $subject['color'],
                'sort_order' => $sortOrder,
                'parent_id' => $parentId,
                'type' => 'lesson'  // These are the actual lessons
            ]);
            $sortOrder++;
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
                'type' => 'lesson'  // These are the actual lessons
            ]);
            $sortOrder++;
        }
    }
}