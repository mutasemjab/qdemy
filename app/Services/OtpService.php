<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OtpService
{
    protected $senderid;
    protected $accname;
    protected $accpass;
    protected $otpLength;
    protected $otpExpiry;

    public function __construct()
    {
        // Load from config or .env
        $this->senderid = config('otp.sender_id', 'HabibaStore');
        $this->accname = config('otp.account_name', 'aliencode');
        $this->accpass = config('otp.account_password', 'jU0nH9pI6mD4vQ2s');
        $this->otpLength = config('otp.length', 4);
        $this->otpExpiry = config('otp.expiry', 300); // 5 minutes in seconds
    }

    /**
     * Generate OTP code
     *
     * @return string
     */
    public function generateOtp(): string
    {
        $min = pow(10, $this->otpLength - 1);
        $max = pow(10, $this->otpLength) - 1;
        return (string) rand($min, $max);
    }

    /**
     * Store OTP in cache
     *
     * @param string $identifier (phone or email)
     * @param string $otp
     * @return void
     */
    public function storeOtp(string $identifier, string $otp): void
    {
        Cache::put('otp_' . $identifier, $otp, $this->otpExpiry);
    }

    /**
     * Verify OTP
     *
     * @param string $identifier
     * @param string $otp
     * @return bool
     */
    public function verifyOtp(string $identifier, string $otp): bool
    {
        $storedOtp = Cache::get('otp_' . $identifier);
        
        if (!$storedOtp) {
            return false;
        }

        if ($storedOtp == $otp) {
            // OTP is correct, remove it from cache
            Cache::forget('otp_' . $identifier);
            return true;
        }

        return false;
    }

    /**
     * Check if OTP exists and is valid
     *
     * @param string $identifier
     * @return bool
     */
    public function otpExists(string $identifier): bool
    {
        return Cache::has('otp_' . $identifier);
    }

    /**
     * Send OTP via SMS
     *
     * @param string $mobile
     * @param string $otp
     * @return array
     */
    public function sendOtpSms(string $mobile, string $otp): array
    {
        $message = "Your OTP code is: $otp. Valid for " . ($this->otpExpiry / 60) . " minutes.";
        $formattedMobile = ltrim($mobile, '+');
        
        // Log the parameters
        Log::info('SMS Gateway Parameters:', [
            'senderid' => $this->senderid,
            'numbers' => $formattedMobile,
            'accname' => $this->accname,
            'message' => $message,
            'original_mobile' => $mobile,
            'otp' => $otp
        ]);
        
        $url = "https://www.josms.net/SMSServices/Clients/Prof/RestSingleSMS/SendSMS?" . http_build_query([
            'senderid' => $this->senderid,
            'numbers' => $formattedMobile,
            'accname' => $this->accname,
            'AccPass' => $this->accpass,
            'msg' => $message
        ]);
        
        // Log URL (hide password)
        Log::info('SMS Gateway URL:', [
            'url' => str_replace($this->accpass, '***HIDDEN***', $url)
        ]);
        
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        curl_close($curl);
        
        // Log response
        Log::info('SMS Gateway Response:', [
            'http_code' => $httpCode,
            'response' => $response,
            'curl_error' => $curlError,
            'mobile' => $formattedMobile
        ]);

        $success = ($httpCode == 200 && empty($curlError));

        return [
            'success' => $success,
            'http_code' => $httpCode,
            'response' => $response,
            'error' => $curlError
        ];
    }

    /**
     * Generate and send OTP
     *
     * @param string $mobile
     * @return array
     */
    public function generateAndSendOtp(string $mobile): array
    {
        $otp = $this->generateOtp();
        $this->storeOtp($mobile, $otp);
        
        $result = $this->sendOtpSms($mobile, $otp);
        
        if ($result['success']) {
            Log::info('OTP sent successfully for mobile: ' . $mobile);
        } else {
            Log::error('OTP sending failed for mobile: ' . $mobile, $result);
        }
        
        return $result;
    }

    /**
     * Check for test OTP (for development/testing)
     *
     * @param string $identifier
     * @param string $otp
     * @return bool
     */
    public function isTestOtp(string $identifier, string $otp): bool
    {
        // You can customize test credentials in config
        $testPhone = config('otp.test_phone', '+962795970357');
        $testOtp = config('otp.test_otp', '2025');
        
        return ($identifier === $testPhone && $otp === $testOtp);
    }
}