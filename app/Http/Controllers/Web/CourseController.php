<?php

namespace  App\Http\Controllers\Web;

use App\Models\Course;
use App\Models\Subject;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\CourseContent;
use App\Models\ContentUserProgress;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{

    public function index(Request $request)
    {
        // Get filter data
        $programms = CategoryRepository()->getMajors();
        $grades    = collect();
        $subjects  = collect();

        // Build main query
        $query = Course::query()
            ->where('status', 'accepted');

        // Program filter
        if ($request->filled('programm_id')) {
            $selectedProgram = Category::find($request->programm_id);
            if ($selectedProgram) {
                // Check if program needs grades
                if (in_array($selectedProgram->ctg_key, ['tawjihi-and-secondary-program', 'elementary-grades-program'])) {
                    // Load grades for this program
                    if ($selectedProgram->ctg_key == 'elementary-grades-program') {
                        $grades = CategoryRepository()->getElementryProgramGrades();
                    } else {
                        $grades = CategoryRepository()->getTawjihiProgrammGrades();
                    }

                    // Filter courses by program through subjects
                    $query->whereHas('subject', function($q) use ($request) {
                        $q->where('programm_id', $request->programm_id);
                    });
                } else {
                    // Programs without grades
                    $query->whereHas('subject', function($q) use ($request) {
                        $q->where('programm_id', $request->programm_id);
                    });

                    // Load subjects for this program directly
                    $subjects = Subject::where('programm_id', $request->programm_id)
                        ->where('field_type_id', null)
                        ->where('is_active', 1)
                        ->where('is_subject', 1)
                        ->get();
                }
            }
        }

        // Grade filter
        if ($request->filled('grade_id')) {
            $query->whereHas('subject', function($q) use ($request) {
                $q->where('grade_id', $request->grade_id);
            });

            // Load subjects for selected grade
            $subjects = Subject::where('grade_id', $request->grade_id)
                ->where('is_active', 1)
                ->where('is_subject', 1)
                ->get();
        }

        // Subject filter
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Search filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title_ar', 'like', "%{$searchTerm}%")
                ->orWhere('title_en', 'like', "%{$searchTerm}%")
                ->orWhere('description_ar', 'like', "%{$searchTerm}%")
                ->orWhere('description_en', 'like', "%{$searchTerm}%")
                ->orWhereHas('subject', function($sq) use ($searchTerm) {
                    $sq->where('name_ar', 'like', "%{$searchTerm}%")
                        ->orWhere('name_en', 'like', "%{$searchTerm}%");
                })
                ->orWhereHas('teacher', function($tq) use ($searchTerm) {
                    $tq->where('name', 'like', "%{$searchTerm}%");
                });
            });
        }
        $courses = $query->latest()->paginate(PGN)->withQueryString();

        return view('web.courses', [
            'courses' => $courses,
            'programms' => $programms,
            'grades' => $grades,
            'subjects' => $subjects,
        ]);
    }

   public function course(Course $course,$slug = null)
    {
        $user = auth_student();
        $contents = $course->contents;
        $mainSections = $course->sections?->where('parent_id',null);
        $freeContents = $contents?->where('is_free',1)->first();
        $user_courses = session()->get('courses', []);
        $user_enrollment_courses = CourseRepository()->getUserCoursesIds($user?->id);
        $is_enrolled = in_array($course->id,$user_enrollment_courses) ? 1 : 0;

        // Prepare locked content information for sequential courses
        $lockedContents = [];
        if($is_enrolled && $user && $course->is_sequential) {
            $lockedContents = $this->getLockedContentsInfo($course, $user->id);
        }

        if($is_enrolled && $user){
            $exams = $course->exams;
            $calculateCourseProgress = $course->calculateCourseProgress($user->id);
        }

        return view('web.course',[
            'user'         => $user,
            'course'       => $course,
            'exams'        => $exams ?? null,
            'mainSections' => $mainSections,
            'contents'     => $contents,
            'freeContents' => $freeContents,
            'user_courses' => $user_courses,
            'user_enrollment_courses' => $user_enrollment_courses,
            'is_enrolled'  => $is_enrolled,
            'lockedContents' => $lockedContents,

            'course_progress'     => $calculateCourseProgress['total_progress'] ?? 0,
            'completed_videos'    => $calculateCourseProgress['completed_videos'] ?? 0,
            'watching_videos'     => $calculateCourseProgress['watching_videos'] ?? 0,
            'total_videos'        => $calculateCourseProgress['total_videos'] ?? 0,
            'completed_exams'     => $calculateCourseProgress['completed_exams'] ?? 0,
            'total_exams'         => $calculateCourseProgress['total_exams'] ?? 0,
        ]);
    }

    /**
     * Get information about locked contents in a sequential course
     */
    private function getLockedContentsInfo(Course $course, $userId)
    {
        $lockedInfo = [];

        foreach($course->contents as $content) {
            // Find the previous content by order
            $previousContent = CourseContent::where('course_id', $course->id)
                ->where('order', '<', $content->order)
                ->orderBy('order', 'desc')
                ->first();

            if($previousContent) {
                // Check if previous content is completed
                $isPreviousCompleted = ContentUserProgress::where('user_id', $userId)
                    ->where('course_content_id', $previousContent->id)
                    ->where('completed', true)
                    ->exists();

                if(!$isPreviousCompleted) {
                    $lockedInfo[$content->id] = [
                        'is_locked' => true,
                        'previous_content_id' => $previousContent->id,
                        'previous_content_title' => $previousContent->title,
                    ];
                }
            }
        }

        return $lockedInfo;
    }


    public function subject_courses($subject)
    {
        $subject   = Subject::FindOrFail($subject);
        $program   = $subject->program;
        $gradeId   = $subject->grade_id;
        if ($program->ctg_key == 'international-program') {
            $gradeId   = null;
        }
        return redirect()->route('courses',"programm_id=".$subject->programm_id."&grade_id=".$gradeId."&subject_id=".$subject->id);
    }
}
