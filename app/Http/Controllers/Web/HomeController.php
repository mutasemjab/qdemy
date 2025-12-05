<?php

namespace  App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\OpinionStudent;
use App\Models\QuestionWebsite;
use App\Models\Setting;
use App\Models\SocialMedia;
use App\Models\SpecialQdemy;
use App\Models\Teacher;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use GuzzleHttp\Client;

class HomeController extends Controller
{
    public function index()
    {
       
        $socialMediaVideos = SocialMedia::all();
        $teachers = Teacher::all();
        $settings = Setting::first(); // Assuming single settings record
        $faqs = QuestionWebsite::take(3)->get(); // Get first 3 FAQs
        $opinionStudents = OpinionStudent::all();
        $blogs = Blog::take(4)->get(); // Get first 4 blogs

        return view('web.home', compact(
            'socialMediaVideos',
            'teachers',
            'settings',
            'faqs',
            'opinionStudents',
            'blogs'
        ));
    }

    /**
     * عرض صفحة خدمة العملاء
     */
    public function customerServices()
    {
        return view('web.customer_services');
    }

    /**
     * حذف ملف من Bunny Storage
     */
    public function deleteFile(Request $request)
    {
        $request->validate([
            'file_path' => 'required|string'
        ]);

        try {
            $deleted = $this->client->delete($request->file_path);

            if ($deleted) {
                Log::info('File deleted successfully', [
                    'file_path' => $request->file_path
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'تم حذف الملف بنجاح'
                ]);
            } else {
                throw new \Exception('فشل في حذف الملف');
            }

        } catch (\Exception $e) {
            Log::error('File deletion failed', [
                'error' => $e->getMessage(),
                'file_path' => $request->file_path
            ]);

            return response()->json([
                'success' => false,
                'message' => 'فشل في حذف الملف: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * الحصول على قائمة الملفات
     */
    public function listFiles()
    {
        try {
            $files = $this->client->list('uploads/');

            return response()->json([
                'success' => true,
                'files' => $files
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to list files', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'فشل في جلب قائمة الملفات: ' . $e->getMessage()
            ], 500);
        }
    }
}
