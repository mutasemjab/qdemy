<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BunnyUploadController extends Controller
{
    public function sign(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer'
        ]);

        $fileName = time() . '_' . Str::random(10) . '.mp4';
        $path = 'courses_contents/' . $request->course_id . '/' . $fileName;

        return response()->json([
            'upload_url' => 'https://storage.bunnycdn.com/' . config('bunny.storage_zone') . '/' . $path,
            'access_key' => env('BUNNY_STORAGE_PASSWORD'),
            'file_path'  => $path
        ]);
    }
}
