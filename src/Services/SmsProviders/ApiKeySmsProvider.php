<?php

namespace Upcodersir\OtpAuth\Services\SmsProviders;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiKeySmsProvider implements SmsProviderInterface
{
    public function sendSms(string $mobile, string $message)
    {
        Log::info($message . ' ' . config('otp.sms_api_url'));
        $apiUrl = config('otp.sms_api_url');
        $apiKey = config('otp.sms_api_key');

        return Http::post($apiUrl, [
            'api_key' => $apiKey,
            'to' => $mobile,
            'message' => $message,
        ]);
    }
}