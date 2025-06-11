<?php

return [
  'valid_time' => 5, // in minutes
  'user_table' => env('OTP_USER_TABLE', 'users'), // changable by end user
  'token_table' => 'tokens',
  // 'sms_client' => \Upcodersir\OtpAuth\Services\SmsProviders\ApiKeySmsProvider::class,
  'sms_client' => \Upcodersir\OtpAuth\Services\SmsProviders\ApiKeySmsProvider::class,
  'sms_api_url' => '',
  'sms_api_key' => '',
  'mobile_containing_table' => '',
  'redirect_to' => '',
  // other options...
];