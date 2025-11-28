<?php

namespace  App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Http\Controllers\Controller;
use App\Models\Follow;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::with('courses.subject');

        // Filter by subject
        if ($request->has('subject') && !empty($request->subject)) {
            $query->whereHas('courses', function($q) use ($request) {
                $q->where('subject_id', $request->subject);
            });
        }

        $teachers = $query->get();
        $subjects = Subject::all();

        return view('web.teachers', compact('teachers', 'subjects'));
    }

    public function show($id)
    {
        $teacher = Teacher::with(['courses' => function($query) {
            $query->with('subject')
                ->orderBy('created_at', 'desc');
        }, 'followers'])->findOrFail($id);
        
        // Check if current user is following this teacher
        $isFollowing = false;
        if (Auth::check()) {
            $isFollowing = Auth::user()->isFollowing($teacher->id);
        }
        
        // Get teacher statistics
        $coursesCount = $teacher->courses()->count();
        $studentsCount = $teacher->courses()->withCount('enrollments')->get()->sum('enrollments_count');
        
        // Get average rating
        $averageRating = 5;
        
      
        
        return view('web.teacher-profile', compact(
            'teacher', 
            'isFollowing', 
            'coursesCount', 
            'studentsCount', 
            'averageRating',
        ));
    }
    public function toggleFollow(Request $request, $teacherId)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => __('front.Please login to follow teachers')
            ], 401);
        }

        $teacher = Teacher::findOrFail($teacherId);
        $user = Auth::user();

        $follow = Follow::where('user_id', $user->id)
                       ->where('teacher_id', $teacherId)
                       ->first();

        if ($follow) {
            // Unfollow
            $follow->delete();
            $isFollowing = false;
            $message = __('front.Unfollowed successfully');
        } else {
            // Follow
            Follow::create([
                'user_id' => $user->id,
                'teacher_id' => $teacherId,
            ]);
            $isFollowing = true;
            $message = __('front.Followed successfully');
        }

        return response()->json([
            'success' => true,
            'is_following' => $isFollowing,
            'message' => $message,
            'followers_count' => $teacher->followersCount()
        ]);
    }
}