<?php

namespace SevenSparky\LaravelMobileAuth\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use SevenSparky\LaravelMobileAuth\Http\Models\Otp;
use SevenSparky\LaravelMobileAuth\Http\Requests\LaravelMobileAuthValidationRequest;

class AuthController extends BaseController
{
    public function login()
    {
        return view('LaravelMobileAuth::login');
    }

    public function dashboard()
    {
        return view('LaravelMobileAuth::dashboard');
    }

    public function OTPLogin()
    {
        $phone = Session::get('phone');
        if (!$phone)
            return redirect()->route('laravel_mobile_auth.login');

        $this->_fireOtpEvent();

        session()->reflash();
        return view('LaravelMobileAuth::otp');
    }

    public function _fireOtpEvent()
    {
        $phone = session()->get('phone');
        $otpRequest = Otp::where('phone', $phone)->first();

        if (!$otpRequest){
            $this->_generateOtp();
            return true;
        }
        $expired_at = $otpRequest->created_at + 120;

        if (time() > $expired_at){
            $this->_generateOtp();
        }

//        dd($expired_at - $otpRequest->created_at);
    }

    public function _generateOtp()
    {
        $phone = Session::get('phone');

        Otp::query()->where('phone', $phone)->delete();

        $code = random_int(1000, 9999);

        $isExist = Otp::where('code', $code)->first();

        if ($isExist)
            $this->_generateOtp();

        Otp::create([
            'phone' => Session::get('phone'),
            'code' => $code
        ]);
    }


    public function OTPCheck(Request $request)
    {
//        dd('kali');

        Session::reflash();
        $request->validate([
            'phone' => 'required|numeric|digits:6|exists:otps,phone',
            'code' => 'required|numeric|digits:4'
        ],[
            'phone.required' => 'شماره موبایل الزامی می باشد',
            'phone.numeric' => 'شماره موبایل باید عدد باشد',
            'phone.exists' => 'شماره موبایل وارد شده معتبر نمی باشد',
            'phone.digits' => 'شماره موبایل باید ۱۱ رقم باشد',
            'code.required' => 'رمز یکبار مصرف الزامی می باشد',
            'code.numeric' => 'رمز یکبار مصرف باید عدد باشد',
            'code.digits' => 'رمز یکبار مصرف باید ۴ رقم باشد'
        ]);

        $phone = $request->input('phone');
        $code = $request->input('code');

        $otpRequest = Otp::query()->where('phone', $phone)->first();
        if ($otpRequest->code != $code)
            return redirect()->back()
            ->withInput([
                'phone' => $phone
            ])
            ->withErrors([
                'code' => 'رمز یکبار مصرف وارد شده معتبر نیست'
            ]);

        $user = User::query()->where('phone', $phone)->first();

        if (!$user){
            User::create(['phone' => $phone]);
            $user = User::where('phone', $phone)->first();
            Auth::loginUsingId($user->id);
        }

        Auth::loginUsingId($user->id);

        Otp::query()->where('phone', $phone)->delete();
        User::query()->where('phone', $phone)->update([
            'attempts_left' => 3,
            'must_login_with_otp' => false
        ]);

        return redirect()->route('laravel_mobile_auth.dashboard')->with([
            'welcome' => true
        ]);
    }

    public function passwordLogin()
    {
        return view('LaravelMobileAuth::password');
    }

    public function checkAuth(LaravelMobileAuthValidationRequest $request)
    {
        $phone = $request->input('phone');
        $user = User::query()->where('phone', $phone)->first();

        if (!$user)
            return redirect()->route('laravel_mobile_auth.otp-login')->with([
                'phone' => $phone,
                'can_login_with_password' => false
            ]);

        if (!$user->password || $user->must_login_with_otp || $user->attempts_left <= 0)
            return redirect()->route('laravel_mobile_auth.otp-login')->with([
                'phone' => $phone
            ]);

        if ($user->attempts_left > 0)
            return redirect()->route('laravel_mobile_auth.password-login')->with([
                'phone' => $phone
            ]);

    }

    public function passwordCheck(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric|digits:6|exists:users,phone',
            'password' => 'required'
        ],[
            'phone.required' => 'شماره موبایل الزامی می باشد',
            'phone.numeric' => 'شماره موبایل باید عدد باشد',
            'phone.digits' => 'شماره موبایل باید ۱۱ رقم باشد',
            'phone.exists' => 'شماره موبایل وارد شده معتبر نمی باشد',
            'password.required' => 'گذرواژه الزامی می باشد',
        ]);

       $phone = $request->input('phone');
       $password = $request->input('password');

       $user = User::query()->where('phone', $phone)->first();

       if (Hash::check($password, $user->password)){
           Auth::loginUsingId($user->id);
           return redirect()->to('/')->with([
               'welcome_message' => true
           ]);
       }

       $user->update([
           'attempts_left' => 3,
           'must_login_with_otp' => false
       ]);
//       $user->decrement('attempts_left', 1);

       return redirect()->back()->withInput(['phone' => $phone])
           ->withErrors(['password' => 'گذرواژه اشتباه است!']);
    }

}
