<?php
namespace App\Services;
use Bunny\Storage\Client;
use GuzzleHttp\Client as HttpClient;
use Bunny\Storage\Region;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class Bunny{

    private $storageClient;
    private $bunny_storage_password;
    private $bunny_account_api_key;
    private $bunny_storage_zone;
    private $bunny_region;

    public function __construct()
    {
        $this->bunny_storage_password = env('BUNNY_STORAGE_PASSWORD');
        $this->bunny_account_api_key  = env('BUNNY_ACCOUNT_API_KEY','2627243e-e4e8-442d-9bb4-9a708a61538309b0bdec-1361-44f8-9dc0-05c1f22d3b77');
        $this->bunny_storage_zone     = env('BUNNY_STORAGE_ZONE');
        $this->bunny_region           = env('BUNNY_REGION');
       
        $this->storageClient = new Client(
            $this->bunny_storage_password,
            $this->bunny_storage_zone,
            $this->bunny_region,
        );
    }

    public function storageClient()
    {
        return $this->storageClient;
    }

    public function GetStorageClient()
    {
        return $this->storageClient();
    }

    /**
     * رفع وحفظ الفيديو لمسار محدد
     * @param $file   = the file to upload
     * @param $folder = the path to save to - default = upload
    */
    public function upload($file,$folder = 'upload',$options = [])
    {

        $originalName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $filePath = $folder . '/' . $fileName;

        // التحقق من وجود الـ client
        if (!$this->storageClient) {
            return response()->json([
                'success' => false,
                'message' => __('مشكلة في خدمة التخزين')
            ], 400);
        }

        // محاولة رفع الملف مع retry logic
        $maxRetries = 2;
        $retryCount = 0;
        $upload = false;

        while ($retryCount < $maxRetries && !$upload) {
            try {
                // تأخير قصير بين المحاولات
                if ($retryCount > 0) {
                    sleep(1);
                }

                $upload = $this->storageClient->upload($file->getPathname(),$filePath);

                if ($upload) {
                    break;
                }

            } catch (\Exception $uploadException) {
                $errorMessage = $this->parseErrorMessage($e->getMessage(), $e->getCode());
                $retryCount++;
                Log::warning('Upload attempt failed', [
                    'attempt' => $retryCount,
                    'error' => $uploadException->getMessage(),
                    'error_code' => $uploadException->getCode(),
                    'error_message' => $errorMessage,
                ]);
                if ($retryCount >= $maxRetries) {
                    return response()->json([
                        'success' => false,
                        'message' => __('فشل رفع الملف')
                    ], 400);
                }
            }
        }

        if ($upload) {
            return response()->json([
                'success'    => true,
                'message'    => __('تم رفع الملف بنجاح'),
                'file_path'  => $filePath,
                'data' => [
                    'upload'        => $upload,
                    'original_name' => $originalName,
                    'file_name'     => $fileName,
                    'file_path'     => $filePath,
                    'file_size'     => $file->getSize(),
                    'mime_type'     => $file->getMimeType()
                ]
            ]);
        } else {
            if ($retryCount >= $maxRetries) {
                return response()->json([
                    'success' => false,
                    'message' => __('فشل رفع الملف')
                ], 400);
            }
        }

    }

    /**
     * حذف الملف من المسار المحفوظ ف الداتا بيس
    */
    public function delete($video_url) {
        $fileExists = $this->exists($video_url);
        if($fileExists){
            $this->storageClient->delete($video_url);
        }
    }
    // $this->storageClient->info(CourseContent::BUNNY_PATH.'/1/1755249566_bz8QG0aW8s.mp4');

    /**
     * التأكد من وجود ملف ف مسار محدد
    */
    public function exists($fullPath)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://storage.bunnycdn.com/'.$this->bunny_storage_zone.'/'.$fullPath, [
            'headers' => [
                'AccessKey' => $this->bunny_storage_password,
                'accept' => '*/*',
        ],
            'http_errors' => false, // منع الـ HTTP exceptions
            'timeout' => 30, // timeout للطلب
            'connect_timeout' => 10, // timeout للاتصال
        ]);
        return $response->getStatusCode() === 200;

        // استخراج اسم الملف من المسار الكامل
        $fileName = basename($fullPath);
        // استخراج المسار (الدليل) بدون اسم الملف
        $directoryPath = dirname($fullPath);
        // الحصول على قائمة الملفات من BunnyCDN
        $files = $this->storageClient->listFiles($directoryPath);
        // تحويل الملفات إلى مصفوفة من الأسماء باستخدام array_column
        $fileNames = array_column($files, 'ObjectName');
        // البحث عن اسم الملف في المصفوفة
        if(is_array($fileNames) && in_array($fileName, $fileNames, true)) return true;
        return false; 
    }

    
    /**
     * حذف كل الملفات ف مسار محدد ف مسار محدد
    */
    public function deleteFiles($directoryPath) 
    {
        $files = $this->storageClient->listFiles($directoryPath);
        if($files && count($files)){
            foreach ($files as $file) {
                $this->storageClient->delete($directoryPath . '/' .$file->ObjectName);
            }
        }
    }
    
    /**
     * تحليل رسائل الأخطاء وتحويلها لرسائل مفهومة
    */
    private function parseErrorMessage($message, $code)
    {
        $errorMappings = [
            6 => 'فشل في الاتصال بالخادم - تحقق من الاتصال بالإنترنت',
            7 => 'فشل في الاتصال بالخادم',
            28 => 'انتهت مهلة الاتصال - الملف كبير جداً أو الاتصال بطيء',
            35 => 'مشكلة في SSL/TLS',
            401 => 'مفتاح API غير صحيح',
            403 => 'غير مسموح بالوصول',
            404 => 'المسار المطلوب غير موجود',
            413 => 'الملف كبير جداً',
            500 => 'خطأ في الخادم'
        ];

        if (isset($errorMappings[$code])) {
            return $errorMappings[$code];
        }

        // البحث عن كلمات مفتاحية في رسالة الخطأ
        if (stripos($message, 'timeout') !== false || stripos($message, 'timed out') !== false) {
            return 'انتهت مهلة الاتصال - حاول مرة أخرى';
        }

        if (stripos($message, 'connection') !== false) {
            return 'مشكلة في الاتصال بالخادم';
        }

        if (stripos($message, 'ssl') !== false || stripos($message, 'certificate') !== false) {
            return 'مشكلة في شهادة الأمان';
        }

        return $message;
    }

}
