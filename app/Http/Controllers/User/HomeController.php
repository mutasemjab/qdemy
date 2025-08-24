<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use GuzzleHttp\Client;

class HomeController extends Controller
{
    public function index()
    {
        return view('user.home');
    }

    /**
     * عرض صفحة خدمة العملاء
     */
    public function customerServices()
    {
        return view('user.customer_services');
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
