**OTP-AUTH**

A Laravel package to send/verify OTP authentication codes

**Installation**

To install this package in your Laravel project, use the following command:

    composer require upcodersir/otp-auth

To publish config file, run the following command:

    php artisan vendor:publish

Running that command, creates a `otp.php` file in your project's config folder, and you need to customize it.

  * valid_time
  * user_table
  * token_table
  * sms_client
  * sms_api_url
  * sms_api_key


Then run `artisan migrate` to migrate tables:

    php artisan migrate 



**Usage**