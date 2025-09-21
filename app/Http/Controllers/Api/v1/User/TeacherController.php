<?php
namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Follow;
use App\Models\Teacher;
use App\Traits\Responses;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    use Responses;

     public function index()
    {
        $data = Teacher::with('user')->get();

        return $this->success_response('Teachers Retrieved successfully', $data);
    }

    public function show(Request $request, $teacherId)
    {
        try {
            // Find teacher
            $teacher = Teacher::with(['user', 'courses'])->find($teacherId);
            
            if (!$teacher) {
                return $this->error_response('Teacher not found', null);
            }

            // Get authenticated user
            $user = $request->user();

            // Check if user is following this teacher
            $isFollowing = false;
            if ($user) {
                $isFollowing = Follow::where('user_id', $user->id)
                                   ->where('teacher_id', $teacherId)
                                   ->exists();
            }

            // Get followers count
            $followersCount = Follow::where('teacher_id', $teacherId)->count();

            // Get courses count
            $coursesCount = $teacher->courses()->count();

            // Build teacher data
            $teacherData = [
                'id' => $teacher->id,
                'name' => $teacher->user->name ?? $teacher->name,
                'name_of_lesson' => $teacher->name_of_lesson,
                'photo' => $teacher->photo ? asset('assets/admin/uploads/' . $teacher->photo) : null,
                'description_ar' => $teacher->description_ar,
                'social_media' => [
                    'facebook' => $teacher->facebook,
                    'instagram' => $teacher->instagram,
                    'youtube' => $teacher->youtube,
                    'whatsapp' => $teacher->whataspp
                ],
                'stats' => [
                    'followers_count' => $followersCount,
                    'courses_count' => $coursesCount
                ],
                'follow_status' => [
                    'is_following' => $isFollowing,
                    'can_follow' => (bool) $user // user must be authenticated to follow
                ],
                'created_at' => $teacher->created_at,
                'updated_at' => $teacher->updated_at
            ];

            // Optionally include recent courses
            if ($teacher->courses && $teacher->courses->count() > 0) {
                $teacherData['recent_courses'] = $teacher->courses()
                    ->latest()
                    ->limit(3)
                    ->get()
                    ->map(function ($course) {
                        return [
                            'id' => $course->id,
                            'title_ar' => $course->title_ar,
                            'title_en' => $course->title_en,
                            'selling_price' => $course->selling_price,
                            'photo' => $course->photo ? asset('assets/admin/uploads/' . $course->photo) : null,
                        ];
                    });
            } else {
                $teacherData['recent_courses'] = [];
            }

            return $this->success_response('Teacher details retrieved successfully', $teacherData);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve teacher details: ' . $e->getMessage(), null);
        }
    }
}
