<?php

namespace App\Http\Controllers\Web;

use App\Models\Subject;
use App\Models\Category;
use App\Http\Controllers\Controller;

class TrainingCoursesController extends Controller
{
    public function index()
    {
        // الحصول على برنامج الدورات التدريبية
        $trainingCourses = Category::where('name_en', 'Training Courses')
            ->orWhere('ctg_key', 'training-courses')
            ->first();

        // إذا لم يكن البرنامج موجوداً
        if (!$trainingCourses) {
            $subjects = collect();
        } else {
            // جلب المواد التابعة لبرنامج الدورات التدريبية فقط
            $subjects = Subject::where('is_active', true)
                ->where('programm_id', $trainingCourses->id)
                ->with(['grade', 'semester', 'program', 'fieldType', 'courses'])
                ->orderBy('sort_order')
                ->get();
        }

        return view('web.training-courses', [
            'subjects' => $subjects,
        ]);
    }
}
