<?php

namespace Upcodersir\OtpAuth\Http\Controllers;

use Upcodersir\OtpAuth\Events\OtpSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Upcodersir\OtpAuth\Services\OtpAuthNotifier;
use Upcodersir\OtpAuth\Services\OtpAuthService;
use Upcodersir\OtpAuth\Services\SmsClient;
use Upcodersir\OtpAuth\Services\SmsProviders\SmsProviderInterface;
use Illuminate\Support\Facades\Session;


class OtpAuthController extends Controller
{
    protected OtpAuthNotifier $notifier;

    /**
     * Constructor to inject OtpAuthNotifier service
     *
     * @param OtpAuthNotifier $notifier
     */
    public function __construct(OtpAuthNotifier $notifier)
    {
        $this->notifier = $notifier;
    }


    public function requestOtp(Request $request)
    {
        $request->validate([
            'identifier' => 'required',
            'type' => 'required|in:sms,email',
        ]);

        try {
            $otp = OtpAuthService::generateOtp($request->identifier);
            $this->notifier->sendOtp($request->identifier, $otp, $request->type);

            $data = ['status' => 'success', 'message' => 'OTP sent successfully.'];

            //send event
            event(new OtpSent($data));

            return response()->json($data, 200);

        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send OTP: ' . $e->getMessage(),
            ], 500);            
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'identifier' => 'required',
            'otp' => 'required',
        ]);

        if (OtpAuthService::validateOtp($request->identifier, $request->otp)) {
            $userTable = config('otp.user_table', 'users');

            $mobileContainingTable = config('otp.mobile_containing_table', 'profiles');

            if($userTable == $mobileContainingTable) {
                $user = DB::table($userTable)->where('email', $request->identifier)
                ->orWhere('mobile', $request->identifier)
                ->first();

            } else {
                $profile = DB::table($mobileContainingTable)->where('mobile', $request->identifier)
                ->first();
                $userId = $profile->user_id;
                $user = DB::table($userTable)->find($userId);
            }

            if (!$user) {
                $userId = DB::table($userTable)->insertGetId([
                    'email' => filter_var($request->identifier, FILTER_VALIDATE_EMAIL) ? $request->identifier : null,
                    'mobile' => preg_match('/^\d+$/', $request->identifier) ? $request->identifier : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $user = DB::table($userTable)->find($userId);
            }
            $user = \App\Models\User::find($user->id);

            $request->session();

            Auth::login($user);
            session()->regenerate();

            if (Auth::check()) {
                return abort(redirect()->route('dashboard'));
            } else {
                dd('User is not authenticated');
            }
            
            return response()->json(['message' => 'Login successful', 'user' => $user]);
        }

        return response()->json(['message' => 'Invalid OTP'], 400);
    }
}
