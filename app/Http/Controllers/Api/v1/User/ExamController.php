<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Repositories\CategoryRepository;
use App\Traits\Responses;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExamController extends Controller
{
    use Responses;

    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

   public function getElectronicExams(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $search = $request->get('search');

            // Get categories data (same as web version)
            $programmsGrades = $this->categoryRepository->getProgrammsGrades();
            $subjectUnderProgrammsGrades = $this->categoryRepository->getSubjectUnderProgrammsGrades();
            $gradesSemesters = $this->categoryRepository->getGradesSemesters();

            // Build exam query (exact same as web version)
            $query = Exam::query()
                ->where('is_active', 1)
                ->where(function($q) {
                    $now = now();
                    $q->where(function($q) use ($now) {
                        $q->whereNull('start_date')
                          ->orWhere('start_date', '<=', $now);
                    })
                    ->where(function($q) use ($now) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', $now);
                    });
                })
                ->where('course_id', null);

            // Add search if provided
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('title_ar', 'like', "%{$search}%")
                      ->orWhere('title_en', 'like', "%{$search}%")
                      ->orWhere('description_ar', 'like', "%{$search}%")
                      ->orWhere('description_en', 'like', "%{$search}%");
                });
            }

            // Load relationships that exist in the model
            $query->with(['course', 'questions', 'creator']);

            // Order by latest
            $query->latest();

            $exams = $query->paginate($perPage);

            // Format exams data for API
            $examsData = $exams->getCollection()->map(function ($exam) {
                return [
                    'id' => $exam->id,
                    'title_ar' => $exam->title_ar,
                    'title_en' => $exam->title_en,
                    'title' => $exam->title, // Uses model accessor for current locale
                    'description_ar' => $exam->description_ar,
                    'description_en' => $exam->description_en,
                    'description' => $exam->description, // Uses model accessor for current locale
                    'slug' => $exam->slug, // Uses model accessor
                    'duration_minutes' => $exam->duration_minutes,
                    'formatted_duration' => $exam->formatted_duration, // Uses model accessor
                    'total_grade' => $exam->total_grade,
                    'passing_grade' => $exam->passing_grade,
                    'attempts_allowed' => $exam->attempts_allowed,
                    'shuffle_questions' => $exam->shuffle_questions,
                    'shuffle_options' => $exam->shuffle_options,
                    'show_results_immediately' => $exam->show_results_immediately,
                    'start_date' => $exam->start_date,
                    'end_date' => $exam->end_date,
                    'is_active' => $exam->is_active,
                    'questions_count' => $exam->questions ? $exam->questions->count() : 0,
                    'course' => $exam->course ? [
                        'id' => $exam->course->id,
                        'title_ar' => $exam->course->title_ar,
                        'title_en' => $exam->course->title_en
                    ] : null,
                    'creator' => $exam->creator ? [
                        'id' => $exam->creator->id,
                        'name' => $exam->creator->name
                    ] : null,
                    'is_available' => $exam->is_available(), // Uses model method
                    'can_take_exam' => $exam->is_available(),
                    'created_at' => $exam->created_at,
                    'updated_at' => $exam->updated_at
                ];
            });

            // Format categories data for API
            $categoriesData = [
                'programms_grades' => $programmsGrades->map(function ($grade) {
                    return [
                        'id' => $grade->id,
                        'name_ar' => $grade->name_ar,
                        'name_en' => $grade->name_en,
                        'ctg_key' => $grade->ctg_key,
                        'level' => $grade->level,
                        'color' => $grade->color,
                        'icon' => $grade->icon,
                        'sort_order' => $grade->sort_order
                    ];
                }),
                'subjects' => $subjectUnderProgrammsGrades->map(function ($subject) {
                    return [
                        'id' => $subject->id,
                        'name_ar' => $subject->name_ar,
                        'name_en' => $subject->name_en,
                        'ctg_key' => $subject->ctg_key,
                        'level' => $subject->level,
                        'color' => $subject->color,
                        'icon' => $subject->icon,
                        'field_type' => $subject->field_type,
                        'is_optional' => $subject->is_optional,
                        'is_ministry' => $subject->is_ministry
                    ];
                }),
                'semesters' => $gradesSemesters->map(function ($semester) {
                    return [
                        'id' => $semester->id,
                        'name_ar' => $semester->name_ar,
                        'name_en' => $semester->name_en,
                        'ctg_key' => $semester->ctg_key,
                        'level' => $semester->level,
                        'sort_order' => $semester->sort_order
                    ];
                })
            ];

            $responseData = [
                'exams' => $examsData,
                'categories' => $categoriesData,
                'filters' => [
                    'search' => $search
                ],
                'pagination' => [
                    'current_page' => $exams->currentPage(),
                    'last_page' => $exams->lastPage(),
                    'per_page' => $exams->perPage(),
                    'total' => $exams->total(),
                    'from' => $exams->firstItem(),
                    'to' => $exams->lastItem(),
                    'has_more_pages' => $exams->hasMorePages()
                ]
            ];

            return $this->success_response('Electronic exams retrieved successfully', $responseData);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve electronic exams: ' . $e->getMessage(), null);
        }
    }

    
}