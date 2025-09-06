<?php

namespace App\Http\Controllers\Panel\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Parentt;
use App\Models\User;

class ParentController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $parentRecord = $user->parentRecord;
        
        // Get children information
        $children = $parentRecord ? $parentRecord->getStudentsWithProgress() : collect();
        $childrenSummary = $parentRecord ? $parentRecord->getChildrenSummary() : [];
        
        return view('panel.parent.dashboard', compact('user', 'children', 'childrenSummary'));
    }
    
    public function profile()
    {
        $user = Auth::user();
        return view('panel.parent.profile', compact('user'));
    }
    
    public function children()
    {
        $user = Auth::user();
        $parentRecord = $user->parentRecord;
        $children = $parentRecord ? $parentRecord->getStudentsWithProgress() : collect();
        
        return view('panel.parent.children', compact('user', 'children'));
    }
    
    public function childReports()
    {
        $user = Auth::user();
        $parentRecord = $user->parentRecord;
        
        // Get children with their recent activity
        $reports = $parentRecord ? $parentRecord->getChildrenRecentActivity(30) : [];
        
        return view('panel.parent.child-reports', compact('user', 'reports'));
    }
    
    public function paymentHistory()
    {
        $user = Auth::user();
        // Get payment history - you would implement this based on your payment system
        $payments = []; // Replace with actual payments query
        
        return view('panel.parent.payment-history', compact('user', 'payments'));
    }
    
    public function addChild()
    {
        $user = Auth::user();
        $availableStudents = $user->getAvailableStudentsToAdd();
        
        return view('panel.parent.add-child', compact('user', 'availableStudents'));
    }
    
    /**
     * Add a child to the parent (AJAX)
     */
    public function addChildSubmit(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);
        
        $user = Auth::user();
        $parentRecord = $user->parentRecord;
        
        if (!$parentRecord) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على سجل الوالد'
            ]);
        }
        
        // Verify the student
        $student = User::where('id', $request->student_id)
                      ->where('role_name', 'student')
                      ->where('activate', 1)
                      ->first();
        
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'الطالب غير موجود أو غير نشط'
            ]);
        }
        
        // Add the student
        $success = $parentRecord->addStudent($request->student_id);
        
        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'تم إضافة الطالب بنجاح',
                'student' => $student
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'الطالب مضاف مسبقاً أو حدث خطأ'
            ]);
        }
    }
    
    /**
     * Remove a child from the parent (AJAX)
     */
    public function removeChild(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);
        
        $user = Auth::user();
        $parentRecord = $user->parentRecord;
        
        if (!$parentRecord) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على سجل الوالد'
            ]);
        }
        
        $success = $parentRecord->removeStudent($request->student_id);
        
        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف الطالب بنجاح'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في الحذف'
            ]);
        }
    }
    
    /**
     * Search for students to add (AJAX)
     */
    public function searchStudents(Request $request)
    {
        $search = $request->get('search', '');
        $user = Auth::user();
        
        $students = $user->getAvailableStudentsToAdd($search);
        
        return response()->json([
            'success' => true,
            'students' => $students
        ]);
    }
}