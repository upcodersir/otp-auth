<?php

namespace Upcodersir\OtpAuth\Services\SmsProviders;

interface SmsProviderInterface
{
    public function sendSms(string $mobile, string $message);
}
