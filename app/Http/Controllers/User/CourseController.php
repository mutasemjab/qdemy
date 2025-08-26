<?php

namespace App\Http\Controllers\User;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\CourseContent;
use App\Models\ContentUserProgress;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::get();
        $courses = Course::paginate(PGN);
        return view('user.courses',[
            'courses' => $courses,
        ]);
    }

    public function course(Course $course,$slug = null)
    {
        $user = auth('user')->user();
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
        return view('user.course',[
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
        $subject = Category::FindOrFail($subject);
        $courses = Course::where('category_id',$subject->id)->latest()->paginate(PGN);
        return view('user.courses',[
            'subject' => $subject,
            'title'   => $subject->name_en,
            'courses' => $courses,
        ]);
    }


    public function international_programm_courses($programm = null)
    {
        $courses              = CourseRepository()->internationalProgramCourses($programm)->paginate(PGN);
        return view('user.courses',[
            'title'    => 'International Program',
            'courses'  => $courses,
        ]);
    }

    public function universities_programm_courses()
    {
        $courses = CourseRepository()->universitiesProgramCourses()->paginate(PGN);
        return view('user.courses',[
            'title'   => 'Universities and Colleges Program',
            'courses' => $courses,
        ]);
    }

}
