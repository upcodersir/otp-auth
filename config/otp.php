<?php

return [
  'valid_time' => 5,
  'user_table' => env('OTP_USER_TABLE', 'users'), // changable by end user
  'token_table' => 'tokens',
  // 'sms_client' => \Upcodersir\OtpAuth\Services\SmsProviders\ApiKeySmsProvider::class,
  'sms_client' => \Upcodersir\OtpAuth\Services\SmsProviders\ApiKeySmsProvider::class,
  'sms_api_url' => '',
  'sms_api_key' => '',
  // other options...
];