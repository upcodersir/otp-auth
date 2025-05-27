<?php

namespace Upcodersir\OtpAuth\Services;

use Carbon\Carbon;
use Upcodersir\OtpAuth\Models\OtpToken;
use Illuminate\Support\Facades\Hash;

class OtpAuthService
{
    public static function generateOtp($identifier)
    {
        $otp = rand(100000, 999999);

        OtpToken::create([
            'identifier' => $identifier,
            'token' => Hash::make($otp),
            'expires_at' => Carbon::now()->addMinutes(config('otp.valid_time')),
        ]);
        
        return $otp;
    }

    public static function validateOtp($identifier, $otp)
    {
        $otpRecord = OtpToken::where('identifier', $identifier)
            ->where('expires_at', '>', Carbon::now())
            ->where('used', false)
            ->first();

        if ($otpRecord && Hash::check($otp, $otpRecord->token)) {
            $otpRecord->update(['used' => true]);
            return true;
        }

        return false;
    }
}
