<?php

namespace Upcodersir\OtpAuth;

use Illuminate\Support\ServiceProvider;
use Upcodersir\OtpAuth\Services\SmsProviders\ApiKeySmsProvider;
use Upcodersir\OtpAuth\Services\SmsClient;
use Illuminate\Filesystem\Filesystem;
use Upcodersir\OtpAuth\Services\OtpAuthNotifier;
use Upcodersir\OtpAuth\Services\SmsProviders\SmsProviderInterface;

class OtpAuthServiceProvider extends ServiceProvider
{
  public function register()
  {
    //registering the config file
    $this->mergeConfigFrom(__DIR__.'/../config/otp.php', 'otpauth');


    $this->app->bind(SmsProviderInterface::class, function ($app) {
      $smsProviderClass = config('otp.sms_client', \Upcodersir\OtpAuth\Services\SmsProviders\ApiKeySmsProvider::class);
      return new $smsProviderClass();
  });

  $this->app->singleton(OtpAuthNotifier::class, function ($app) {
      return new OtpAuthNotifier($app->make(SmsProviderInterface::class));
  });


  }

  public function boot()
  {
    //exporting the config file
    if ($this->app->runningInConsole()) {

      $this->publishes([
        __DIR__.'/../config/otp.php' => config_path('otp.php'),
      ], 'config');
  
    }
    
    //Registering Routes
    $this->loadRoutesFrom(__DIR__.'/../routes/otp.php');


    //Load Migrations
    $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
  }
}
