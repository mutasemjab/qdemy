<?php

namespace App\Http\Controllers\Panel\Teacher;

use App\Http\Controllers\Controller;
use App\Traits\HasCommunity;
use App\Traits\HasNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    use HasNotifications,HasCommunity;
    
    public function dashboard()
    {
        $user = Auth::user();

        $notifications = $this->getUserNotifications();
         // Get community posts for the dashboard
        $posts = $this->getCommunityPosts(20);

        return view('panel.teacher.dashboard', compact('user','notifications','posts'));
    }
    
       public function markAsRead($id)
    {
        return $this->markNotificationAsRead($id);
    }

   public function updateAccount(Request $request)
    {
        $user = Auth::guard('user')->user();

        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email,'.$user->id,
            'phone'          => 'nullable|string|max:20',
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png',
            'name_of_lesson' => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'facebook'       => 'nullable|url',
            'instagram'      => 'nullable|url',
            'youtube'        => 'nullable|url',
            'whatsapp'       => 'nullable|string|max:20',
        ]);

        // Update user basic info
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $filename = uploadImage('assets/admin/uploads', $request->photo);
            $user->photo = $filename;
        }

        $user->save();

        // Update or create teacher record
        $teacherData = [
            'name'           => $request->name,
            'name_of_lesson' => $request->name_of_lesson,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'facebook'       => $request->facebook,
            'instagram'      => $request->instagram,
            'youtube'        => $request->youtube,
            'whataspp'       => $request->whatsapp, // Note: keeping your typo "whataspp" to match your schema
            'photo'          => $user->photo,
            'user_id'        => $user->id,
        ];

        // Update or create teacher record
        $user->teacher()->updateOrCreate(
            ['user_id' => $user->id],
            $teacherData
        );

        return back()->with('success', __('panel.account_updated'));
}
    
    public function students()
    {
        $user = Auth::user();
        // Get teacher's students
        $students = []; // Replace with actual students query
        return view('panel.teacher.students', compact('user', 'students'));
    }
    
    public function courses()
    {
        $user = Auth::user();
        // Get teacher's courses
        $courses = []; // Replace with actual courses query
        return view('panel.teacher.courses', compact('user', 'courses'));
    }
   
    
    public function createCourse()
    {
        $user = Auth::user();
        return view('panel.teacher.create-course', compact('user'));
    }

      // Community methods
     public function createPost(Request $request)
    {
        return $this->handleCreatePost($request);
    }

    public function toggleLike(Request $request)
    {
        return $this->handleToggleLike($request);
    }

    public function addComment(Request $request)
    {
        return $this->handleAddComment($request);
    }

}