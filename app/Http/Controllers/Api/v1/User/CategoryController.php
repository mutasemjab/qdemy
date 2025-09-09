<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Traits\Responses;
use App\Repositories\CategoryRepository;
use App\Repositories\SubjectRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use Responses;

    protected $categoryRepo;
    protected $subjectRepo;

    public function __construct(CategoryRepository $categoryRepo, SubjectRepository $subjectRepo)
    {
        $this->categoryRepo = $categoryRepo;
        $this->subjectRepo = $subjectRepo;
    }


    /**
     * Get elementary program grades
     * GET /api/categories/grades/elementary
     */
    public function getElementaryGrades()
    {
        try {
            $grades = $this->categoryRepo->getElementryProgramGrades();
            
            $data = $grades->map(function ($grade) {
                return [
                    'id' => $grade->id,
                    'name_ar' => $grade->name_ar,
                    'name_en' => $grade->name_en,
                    'level' => $grade->level,
                    'ctg_key' => $grade->ctg_key,
                    'sort_order' => $grade->sort_order,
                    'semesters' => $this->categoryRepo->getGradeSemesters($grade->id)->map(function ($semester) {
                        return [
                            'id' => $semester->id,
                            'name_ar' => $semester->name_ar,
                            'name_en' => $semester->name_en,
                            'ctg_key' => $semester->ctg_key,
                            'sort_order' => $semester->sort_order
                        ];
                    })
                ];
            });

            return $this->success_response('Elementary grades retrieved successfully', $data);
        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve elementary grades', $e->getMessage());
        }
    }

       /**
     * Get semesters for a specific grade
     * GET /api/categories/grades/{gradeId}/semesters
     */
    public function getGradeSemesters($gradeId)
    {
        try {
            $semesters = $this->categoryRepo->getGradeSemesters($gradeId);
            
            $data = $semesters->map(function ($semester) {
                return [
                    'id' => $semester->id,
                    'name_ar' => $semester->name_ar,
                    'name_en' => $semester->name_en,
                    'ctg_key' => $semester->ctg_key,
                    'level' => $semester->level,
                    'sort_order' => $semester->sort_order,
                    'parent_id' => $semester->parent_id,
                    'subjects' => $this->subjectRepo->getSubjectsForSemester($semester),
                ];
            });

            return $this->success_response('Grade semesters retrieved successfully', $data);
        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve grade semesters', $e->getMessage());
        }
    }

    /**
     * Get Tawjihi program grades
     * GET /api/categories/grades/tawjihi
     */
    public function getTawjihiGrades()
    {
        try {
            $grades = $this->categoryRepo->getTawjihiProgrammGrades();
            
            $data = $grades->map(function ($grade) {
                return [
                    'id' => $grade->id,
                    'name_ar' => $grade->name_ar,
                    'name_en' => $grade->name_en,
                    'level' => $grade->level,
                    'ctg_key' => $grade->ctg_key,
                    'sort_order' => $grade->sort_order,
                    'is_final_year' => $grade->ctg_key === 'final_year',
                    'semesters' => $this->categoryRepo->getGradeSemesters($grade->id)->map(function ($semester) {
                        return [
                            'id' => $semester->id,
                            'name_ar' => $semester->name_ar,
                            'name_en' => $semester->name_en,
                            'ctg_key' => $semester->ctg_key,
                            'sort_order' => $semester->sort_order
                        ];
                    })
                ];
            });

            return $this->success_response('Tawjihi grades retrieved successfully', $data);
        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve Tawjihi grades', $e->getMessage());
        }
    }


    /**
     * Get Tawjihi final grade fields
     * GET /api/categories/tawjihi/final-grade-fields
     */
    public function getTawjihiFinalGradeFields()
    {
        try {
            $fields = $this->categoryRepo->getTawjihiLastGradeFieldes();
            
            $data = $fields->map(function ($field) {
                return [
                    'id' => $field->id,
                    'name_ar' => $field->name_ar,
                    'name_en' => $field->name_en,
                    'level' => $field->level,
                    'ctg_key' => $field->ctg_key,
                    'sort_order' => $field->sort_order,
                    'ministry_subjects' => $this->subjectRepo->getTawjihiFinalGradesFieldMinistrySubjects($field)->map(function ($subject) {
                        return [
                            'id' => $subject->id,
                            'name_ar' => $subject->name_ar,
                            'name_en' => $subject->name_en,
                            'icon' => $subject->icon,
                            'color' => $subject->color
                        ];
                    }),
                    'school_subjects' => $this->subjectRepo->getTawjihiFinalGradesFieldSchoolSubjects($field)->map(function ($subject) {
                        return [
                            'id' => $subject->id,
                            'name_ar' => $subject->name_ar,
                            'name_en' => $subject->name_en,
                            'icon' => $subject->icon,
                            'color' => $subject->color
                        ];
                    })
                ];
            });

            return $this->success_response('Tawjihi final grade fields retrieved successfully', $data);
        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve Tawjihi final grade fields', $e->getMessage());
        }
    }

    public function getTawjihiFirstYear()
    {
        try {
            $tawjihiFirstYear  = $this->categoryRepo->getTawjihiFirstGrade();

            $data = [
                'id' => $tawjihiFirstYear->id,
                'name_ar' => $tawjihiFirstYear->name_ar,
                'name_en' => $tawjihiFirstYear->name_en,
                'level' => $tawjihiFirstYear->level,
                'ctg_key' => $tawjihiFirstYear->ctg_key,
                'sort_order' => $tawjihiFirstYear->sort_order,
                'ministry_subjects' => $this->subjectRepo->getTawjihiFirstGradesMinistrySubjects()->map(function ($subject) {
                    return [
                        'id' => $subject->id,
                        'name_ar' => $subject->name_ar,
                        'name_en' => $subject->name_en,
                        'icon' => $subject->icon,
                        'color' => $subject->color
                    ];
                }),
                'school_subjects' => $this->subjectRepo->getTawjihiFirstGradesSchoolSubjects()->map(function ($subject) {
                    return [
                        'id' => $subject->id,
                        'name_ar' => $subject->name_ar,
                        'name_en' => $subject->name_en,
                        'icon' => $subject->icon,
                        'color' => $subject->color
                    ];
                }),
            ];

            return $this->success_response('Tawjihi first year retrieved successfully', $data);
        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve Tawjihi first year', $e->getMessage());
        }
    }


    /**
     * Get international program
     * GET /api/categories/international-program
     */
    public function getInternationalPrograms() // Note: changed method name to plural
    {
        try {
            $programs = $this->categoryRepo->getInternationalPrograms();
            
            if ($programs->isEmpty()) {
                return $this->error_response('International programs not found', null);
            }

            $data = $programs->map(function ($program) {
                return [
                    'id' => $program->id,
                    'name_ar' => $program->name_ar,
                    'name_en' => $program->name_en,
                    'description_ar' => $program->description_ar,
                    'description_en' => $program->description_en,
                    'ctg_key' => $program->ctg_key,
                    'subjects' => $this->subjectRepo->getinternationalProgramSubjects($program)->map(function ($subject) {
                        return [
                            'id' => $subject->id,
                            'name_ar' => $subject->name_ar,
                            'name_en' => $subject->name_en,
                            'icon' => $subject->icon,
                            'color' => $subject->color,
                            'sort_order' => $subject->sort_order
                        ];
                    })
                ];
            });

            return $this->success_response('International programs retrieved successfully', $data);
        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve international programs', $e->getMessage());
        }
    }

    /**
     * Get universities program
     * GET /api/categories/universities-program
     */
    public function getUniversitiesProgram()
    {
        try {
            $program = $this->categoryRepo->getUniversitiesProgram();
            
            if (!$program) {
                return $this->error_response('Universities program not found', null);
            }

            $data = [
                'id' => $program->id,
                'name_ar' => $program->name_ar,
                'name_en' => $program->name_en,
                'description_ar' => $program->description_ar,
                'description_en' => $program->description_en,
                'ctg_key' => $program->ctg_key,
                'subjects' => $this->subjectRepo->getuniversitiesProgramSubjects()->map(function ($subject) {
                    return [
                        'id' => $subject->id,
                        'name_ar' => $subject->name_ar,
                        'name_en' => $subject->name_en,
                        'icon' => $subject->icon,
                        'color' => $subject->color,
                        'sort_order' => $subject->sort_order
                    ];
                })
            ];

            return $this->success_response('Universities program retrieved successfully', $data);
        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve universities program', $e->getMessage());
        }
    }

    

}