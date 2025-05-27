<?php

namespace Upcodersir\OtpAuth\Services;

use Upcodersir\OtpAuth\Services\SmsProviders\SmsProviderInterface;

class SmsClient
{
    protected SmsProviderInterface $provider;

    public function __construct(SmsProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function send(string $mobile, string $message)
    {
        return $this->provider->sendSms($mobile, $message);
    }
}
