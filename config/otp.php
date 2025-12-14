<?php
// config/otp.php
return [
    'length' => env('OTP_LENGTH', 4), // 4-digit OTP
    'expiry' => env('OTP_EXPIRY', 300), // 5 minutes
    'test_phone' => env('OTP_TEST_PHONE', '+962795970357'),
    'test_otp' => env('OTP_TEST_OTP', '2025'),
    'sender_id' => env('SMS_SENDER_ID', 'QdemyJo'),
    'account_name' => env('SMS_ACCOUNT_NAME', 'aliencode'),
    'account_password' => env('SMS_ACCOUNT_PASSWORD', 'jU0nH9pI6mD4vQ2s'),
];