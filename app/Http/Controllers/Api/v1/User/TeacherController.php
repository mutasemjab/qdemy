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
            // Find teacher with only accepted courses
            $teacher = Teacher::with(['user', 'courses' => function ($q) {
                $q->where('status', 'accepted'); // <-- only accepted courses
            }])->find($teacherId);

            if (!$teacher) {
                return $this->error_response('Teacher not found', null);
            }

            // Get authenticated user
            $user = $request->user();

            // Check if user is following this teacher
            $isFollowing = $user
                ? Follow::where('user_id', $user->id)
                ->where('teacher_id', $teacherId)
                ->exists()
                : false;

            // Get followers count
            $followersCount = Follow::where('teacher_id', $teacherId)->count();

            // Get courses count (only accepted)
            $coursesCount = $teacher->courses()->count();

            // Build teacher data
            $teacherData = [
                'id' => $teacher->id,
                'name' => $teacher->user->name ?? $teacher->name,
                'name_of_lesson' => $teacher->name_of_lesson,
                'photo' => $teacher->photo ? asset('assets/admin/uploads/' . $teacher->photo) : asset('assets_front/images/Profile-picture.jpg'),
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
                    'can_follow' => (bool) $user
                ],
                'created_at' => $teacher->created_at,
                'updated_at' => $teacher->updated_at
            ];

            // Include recent accepted courses only
            $recentCourses = $teacher->courses()
                ->where('status', 'accepted') // <-- filter accepted
                ->latest()
                ->get()
                ->map(function ($course) {
                    return [
                        'id' => $course->id,
                        'title_ar' => $course->title_ar,
                        'title_en' => $course->title_en,
                        'selling_price' => $course->selling_price,
                        'photo' => $course->photo ? asset('assets/admin/uploads/' . $course->photo) : asset('assets_front/images/Profile-picture.jpg'),
                    ];
                });

            $teacherData['recent_courses'] = $recentCourses;

            return $this->success_response('Teacher details retrieved successfully', $teacherData);
        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve teacher details: ' . $e->getMessage(), null);
        }
    }
}
