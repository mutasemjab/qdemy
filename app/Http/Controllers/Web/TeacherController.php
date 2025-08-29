<?php

namespace  App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Category;
use App\Models\Grade;
use App\Models\Semester;
use App\Http\Controllers\Controller;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::with('courses');

        // Filter by subject/category
        if ($request->has('category') && !empty($request->category)) {
            $query->whereHas('courses', function($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }

        $teachers = $query->get();
        $categories = Category::all();

        return view('web.teachers', compact('teachers', 'categories',));
    }

    public function show($id)
    {
        $teacher = Teacher::with('courses.category')->findOrFail($id);
        
        return view('web.teacher-profile', compact('teacher'));
    }
}