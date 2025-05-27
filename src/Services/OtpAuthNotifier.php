<?php

namespace Upcodersir\OtpAuth\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Upcodersir\OtpAuth\Services\SmsProviders\SmsProviderInterface;


class OtpAuthNotifier
{
    //protected $smsClient;
    protected SmsProviderInterface $smsProvider;

    public function __construct(SmsProviderInterface $smsProvider)
    {
        $this->smsProvider = $smsProvider;
    }

    public function sendOtp($identifier, $otp, $type)
    {
        if ($type === 'sms') {
            return self::sendSms($identifier, $otp);
        } else {
            return self::sendEmail($identifier, $otp);
        }
    }

    private function sendSms($mobile, $otp)
    {
        try {
            $this->smsProvider->sendSms($mobile, $otp);
        } catch (\Exception $e) {
            Log::error("SMS sending failed: " . $e->getMessage());
        }
    }

    private static function sendEmail($email, $otp)
    {
        Mail::raw("Your OTP is: $otp", function ($message) use ($email) {
            $message->to($email)->subject('Your OTP Code');
        });
    }
}
