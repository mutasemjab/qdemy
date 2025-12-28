<?php

namespace App\Services;

use Bunny\Storage\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class Bunny
{

    private $storageClient;
    private $bunny_storage_password;
    private $secret;
    private $storage_zone;
    private $region;
    private $baseUrl;
    public $client;

    public function __construct()
    {
        $this->bunny_storage_password = env('BUNNY_STORAGE_PASSWORD');
        $this->secret  = config('bunny.secret');
        $this->storage_zone     = config('bunny.storage_zone');
        $this->region           = config('bunny.region');
        $this->baseUrl = config('bunny.base_url');

        $this->client = Http::baseUrl($this->baseUrl)->withToken($this->secret)->withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ]);

        $this->storageClient = new Client(
            $this->bunny_storage_password,
            $this->storage_zone,
            $this->region,
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
    public function upload($file, $folder = 'upload', $options = [])
    {
        $originalName = $file->getClientOriginalName();
        $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $filePath = $folder . '/' . $fileName;

        // التحقق من وجود الـ client
        if (!$this->storageClient) {
            Log::error('Bunny storage client not initialized');
            return response()->json([
                'success' => false,
                'message' => __('مشكلة في خدمة التخزين')
            ], 400);
        }

        // Log upload attempt
        Log::info('Starting file upload to Bunny', [
            'original_name' => $originalName,
            'file_path' => $filePath,
            'file_size' => $file->getSize()
        ]);

        // محاولة رفع الملف مع retry logic
        $maxRetries = 3;
        $retryCount = 0;
        $upload = false;
        $lastException = null;

        while ($retryCount < $maxRetries && !$upload) {
            try {
                // تأخير قصير بين المحاولات
                if ($retryCount > 0) {
                    sleep(2);
                }

                Log::info('Upload attempt', ['attempt' => $retryCount + 1, 'file_path' => $filePath]);

                $upload = $this->storageClient->upload($file->getPathname(), $filePath);

                if ($upload) {
                    Log::info('Upload successful', ['file_path' => $filePath]);
                    break;
                }
            } catch (\Exception $uploadException) {
                $lastException = $uploadException;
                $errorMessage = $this->parseErrorMessage($uploadException->getMessage(), $uploadException->getCode());
                $retryCount++;

                Log::warning('Upload attempt failed', [
                    'attempt' => $retryCount,
                    'error' => $uploadException->getMessage(),
                    'error_code' => $uploadException->getCode(),
                    'error_message' => $errorMessage,
                    'file_path' => $filePath
                ]);
            }
        }

        // Check final result
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
            // Log final failure
            Log::error('Final upload failure', [
                'file_path' => $filePath,
                'retries' => $retryCount,
                'last_exception' => $lastException ? $lastException->getMessage() : 'Unknown error'
            ]);

            return response()->json([
                'success' => false,
                'message' => __('فشل رفع الملف') . ($lastException ? ': ' . $this->parseErrorMessage($lastException->getMessage(), $lastException->getCode()) : '')
            ], 400);
        }
    }

    /**
     * حذف الملف من المسار المحفوظ ف الداتا بيس
     */
    public function delete($video_url)
    {
        try {
            $fileExists = $this->exists($video_url);
            if ($fileExists) {
                $result = $this->storageClient->delete($video_url);
                Log::info('File deleted', ['file_path' => $video_url, 'result' => $result]);
                return $result;
            }
            Log::warning('Attempted to delete non-existent file', ['file_path' => $video_url]);
            return false;
        } catch (\Exception $e) {
            Log::error('Error deleting file', [
                'file_path' => $video_url,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * التأكد من وجود ملف ف مسار محدد
     */
    public function exists($fullPath)
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', 'https://storage.bunnycdn.com/' . $this->storage_zone . '/' . $fullPath, [
                'headers' => [
                    'AccessKey' => $this->bunny_storage_password,
                    'accept' => '*/*',
                ],
                'http_errors' => false,
                'timeout' => 30,
                'connect_timeout' => 10,
            ]);

            $exists = $response->getStatusCode() === 200;
            Log::info('File existence check', [
                'file_path' => $fullPath,
                'exists' => $exists,
                'status_code' => $response->getStatusCode()
            ]);

            return $exists;
        } catch (\Exception $e) {
            Log::error('Error checking file existence', [
                'file_path' => $fullPath,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get file content for download
     */
    public function getFileContent($fullPath)
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', 'https://storage.bunnycdn.com/' . $this->storage_zone . '/' . $fullPath, [
                'headers' => [
                    'AccessKey' => $this->bunny_storage_password,
                    'accept' => '*/*',
                ],
                'timeout' => 60,
                'connect_timeout' => 10,
            ]);

            if ($response->getStatusCode() === 200) {
                return $response->getBody()->getContents();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Error getting file content', [
                'file_path' => $fullPath,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * حذف كل الملفات ف مسار محدد ف مسار محدد
     */
    public function deleteFiles($directoryPath)
    {
        try {
            $files = $this->storageClient->listFiles($directoryPath);
            if ($files && count($files)) {
                foreach ($files as $file) {
                    $this->storageClient->delete($directoryPath . '/' . $file->ObjectName);
                }
            }
            Log::info('Directory files deleted', ['directory' => $directoryPath]);
        } catch (\Exception $e) {
            Log::error('Error deleting directory files', [
                'directory' => $directoryPath,
                'error' => $e->getMessage()
            ]);
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
            return 'انتهت مهلة الاتصال  حاول مرة أخرى';
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
