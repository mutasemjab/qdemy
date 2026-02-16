<?php

namespace App\Http\Controllers\Panel\Student;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Traits\HasCommunity;
use App\Traits\HasNotifications;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class StudentController extends Controller
{
    use HasNotifications, HasCommunity;

    public function dashboard()
    {
        $user    = auth_student();
        $notifications = $this->getUserNotifications();

        // Get community posts for the dashboard
        $posts = $this->getCommunityPosts(20);

        $userExamsResults    = collect();
        $userCourses         = collect();
        if ($user) {
            $userExamsResults    = $user->result_attempts();
            $userCourses         = $user->courses;
        }
        return view('panel.student.dashboard', compact('userCourses', 'userExamsResults', 'user', 'notifications', 'posts'));
    }

    public function markAsRead($id)
    {
        return $this->markNotificationAsRead($id);
    }

    public function updateAccount(Request $request)
    {
        try {
            $user = Auth::guard('user')->user();

            $request->validate([
                'name'   => 'required|string|max:255',
                'email'  => 'required|email|unique:users,email,' . $user->id,
                'phone'  => 'nullable|string|max:20|unique:users,phone,' . $user->id,
                'photo'  => 'nullable|image|mimes:jpg,jpeg,png',
            ], [
                'name.required' => 'الاسم مطلوب',
                'name.max' => 'الاسم يجب أن لا يتجاوز 255 حرف',
                'email.required' => 'البريد الإلكتروني مطلوب',
                'email.email' => 'البريد الإلكتروني غير صحيح',
                'email.unique' => 'البريد الإلكتروني مستخدم من قبل',
                'phone.unique' => 'رقم الهاتف مستخدم من قبل',
                'phone.max' => 'رقم الهاتف يجب أن لا يتجاوز 20 رقم',
                'photo.image' => 'الملف يجب أن يكون صورة',
                'photo.mimes' => 'الصورة يجب أن تكون من نوع: jpg, jpeg, png',
            ]);

            $user->name  = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;

            // رفع الصورة
            if ($request->hasFile('photo')) {
                $filename = uploadImage('assets/admin/uploads', $request->photo);
                $user->photo = $filename;
            }

            $user->save();

            return back()->with('success', 'تم تحديث الحساب بنجاح');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء تحديث الحساب: ' . $e->getMessage())->withInput();
        }
    }

    public function courses()
    {
        $user = auth_student();
        $userCourses = collect();

        if ($user) {
            $userCourses = $user->courses;
        }

        return view('panel.student.courses', compact('user', 'userCourses'));
    }

    public function results()
    {
        $user = Auth::user();
        $results = [];
        return view('panel.student.results', compact('user', 'results'));
    }

    // Community methods - delegate to trait
    public function community()
    {
        $user = Auth::user();
        $posts = $this->getCommunityPosts(20);

        return view('panel.student.community', compact('user', 'posts'));
    }

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

    public function addReply(Request $request)
    {
        return $this->handleAddReply($request);
    }
}
