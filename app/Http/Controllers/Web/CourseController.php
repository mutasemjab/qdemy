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
        $query = Course::query();
        // ->where('is_active', 1);

        // Program filter
        if ($request->filled('programm_id')) {
            $selectedProgram = Category::find($request->programm_id);
            if ($selectedProgram) {
                // Check if program needs grades
                if (in_array($selectedProgram->ctg_key, ['tawjihi-and-secondary-program', 'elementary-grades-program'])) {
                    // Load grades for this program
                    if ($selectedProgram->ctg_key == 'elementary-grades-program') {
                        // dd(CategoryRepository()->getElementryProgramGrades());
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
                        ->where('is_active', 1)
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

        if($is_enrolled && $user){
            $completedVideos    = ContentUserProgress::where('user_id', $user->id)->where('completed', true)->count();
            $inProgressVideos   = ContentUserProgress::where('user_id', $user->id)->where('completed', false)->count();
            $totalVideos        = CourseContent::where('content_type', 'video')->count();
            $exams              = $course->exams;
            $progressPercentage = $totalVideos > 0 ? ($completedVideos / $totalVideos) * 100 : 0;
            $calculateCourseProgress = $this->calculateCourseProgress($user->id, $course->id);
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

            'completedVideos'     => $completedVideos ?? 0,
            'inProgressVideos'    => $inProgressVideos ?? 0,
            'progressPercentage'  => round($progressPercentage ?? 0, 2) ?? 0,

            'course_progress'     => $calculateCourseProgress['course_progress'] ?? 0,
            'completed_videos'    => $calculateCourseProgress['completed_videos'] ?? 0,
            'watching_videos'     => $calculateCourseProgress['watching_videos'] ?? 0,
            'total_videos'        => $calculateCourseProgress['total_videos'] ?? 0,
        ]);
    }

    private function calculateCourseProgress($userId, $courseId)
    {
        // Get all video contents for the course
        $totalVideos = CourseContent::where('course_id', $courseId)
            ->where('content_type', 'video')
            ->count();

        if ($totalVideos == 0) {
            return [
                'course_progress' => 0,
                'completed_videos' => 0,
                'watching_videos' => 0,
                'total_videos' => 0
            ];
        }

        // Get user progress for this course
        $completedVideos = ContentUserProgress::join('course_contents', 'content_user_progress.course_content_id', '=', 'course_contents.id')
            ->where('content_user_progress.user_id', $userId)
            ->where('course_contents.course_id', $courseId)
            ->where('course_contents.content_type', 'video')
            ->where('content_user_progress.completed', true)
            ->count();

        $watchingVideos = ContentUserProgress::join('course_contents', 'content_user_progress.course_content_id', '=', 'course_contents.id')
            ->where('content_user_progress.user_id', $userId)
            ->where('course_contents.course_id', $courseId)
            ->where('course_contents.content_type', 'video')
            ->where('content_user_progress.completed', false)
            ->where('content_user_progress.watch_time', '>', 0)
            ->count();

        $courseProgress = ($completedVideos / $totalVideos) * 100;

        return [
            'course_progress' => round($courseProgress, 2),
            'completed_videos' => $completedVideos,
            'watching_videos' => $watchingVideos,
            'total_videos' => $totalVideos
        ];
    }

    public function subject_courses($subject)
    {
        $subject = Subject::FindOrFail($subject);
        $courses = Course::where('subject_id',$subject->id)->latest()->paginate(PGN);
        return view('web.courses',[
            'subject' => $subject,
            'title'   => $subject->name_en,
            'courses' => $courses,
        ]);
    }

}
