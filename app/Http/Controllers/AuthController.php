<?php

namespace App\Http\Controllers;

use App\Mail\CustomerOtpMail;
use App\Mail\LoginOtpMail;
use App\Models\CustomerActionOtp;
use App\Models\CustomerLoginOtp;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function requestRegisterOtp(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_num' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
        ]);

        $email = Str::lower(trim($validated['email']));

        // Rate limiting disabled for local development
        // $minuteKey = 'register-otp-minute:' . $email;
        // $hourKey = 'register-otp-hour:' . $email;
        // if (RateLimiter::tooManyAttempts($minuteKey, 1) || RateLimiter::tooManyAttempts($hourKey, 5)) {
        //     return back()->withErrors([
        //         'email' => 'Too many OTP requests. Please try again later.',
        //     ])->withInput();
        // }
        // RateLimiter::hit($minuteKey, 60);
        // RateLimiter::hit($hourKey, 3600);

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(5);

        CustomerActionOtp::query()
            ->where('email', $email)
            ->where('action', 'register')
            ->whereNull('consumed_at')
            ->update(['consumed_at' => now()]);

        CustomerActionOtp::create([
            'email' => $email,
            'action' => 'register',
            'payload' => [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'contact_num' => $validated['contact_num'],
                'email' => $email,
            ],
            'code_hash' => hash('sha256', $code),
            'expires_at' => $expiresAt,
        ]);

        Mail::to($email)->send(new CustomerOtpMail(
            'Verify your NCB account',
            'Use this code to complete your registration:',
            $code,
            5
        ));

        return back()->with('success', 'We sent a verification code to your email.')->withInput();
    }

    public function verifyRegisterOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email|max:255',
            'otp_code' => 'required|string',
        ]);

        $email = Str::lower(trim($validated['email']));

        if (Customer::query()->whereRaw('LOWER(email) = ?', [$email])->exists()) {
            return back()->withErrors([
                'email' => 'Email is already registered.',
            ])->onlyInput('email');
        }

        $otp = CustomerActionOtp::query()
            ->where('email', $email)
            ->where('action', 'register')
            ->whereNull('consumed_at')
            ->where('expires_at', '>=', now())
            ->orderByDesc('created_at')
            ->first();

        if (!$otp || !hash_equals($otp->code_hash, hash('sha256', trim($validated['otp_code'])))) {
            return back()->withErrors([
                'otp_code' => 'Invalid or expired code.',
            ])->onlyInput('email');
        }

        $payload = $otp->payload ?? [];

        $customer = Customer::create([
            'first_name' => $payload['first_name'] ?? '',
            'last_name' => $payload['last_name'] ?? '',
            'contact_num' => $payload['contact_num'] ?? '',
            'email' => $payload['email'] ?? $email,
            'password' => Hash::make(Str::random(32)),
        ]);

        $otp->update(['consumed_at' => now()]);

        Auth::guard('customer')->login($customer);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Registration successful!');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function requestOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $email = Str::lower(trim($validated['email']));
        $customer = Customer::query()->whereRaw('LOWER(email) = ?', [$email])->first();

        if (!$customer) {
            return back()->withErrors([
                'email' => 'Account not found.',
            ])->onlyInput('email');
        }

        // Rate limiting disabled for local development
        // $minuteKey = 'otp-minute:' . $email;
        // $hourKey = 'otp-hour:' . $email;
        // if (RateLimiter::tooManyAttempts($minuteKey, 1) || RateLimiter::tooManyAttempts($hourKey, 5)) {
        //     return back()->withErrors([
        //         'email' => 'Too many OTP requests. Please try again later.',
        //     ])->onlyInput('email');
        // }
        // RateLimiter::hit($minuteKey, 60);
        // RateLimiter::hit($hourKey, 3600);

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(5);

        CustomerLoginOtp::query()
            ->where('email', $customer->email)
            ->whereNull('consumed_at')
            ->update(['consumed_at' => now()]);

        CustomerLoginOtp::create([
            'email' => $customer->email,
            'code_hash' => hash('sha256', $code),
            'expires_at' => $expiresAt,
        ]);

        Mail::to($customer->email)->send(new LoginOtpMail($code, 5));

        return back()->with('success', 'We sent a one-time code to your email.')->onlyInput('email');
    }

    public function verifyOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|string',
        ]);

        $email = Str::lower(trim($validated['email']));
        $customer = Customer::query()->whereRaw('LOWER(email) = ?', [$email])->first();

        if (!$customer) {
            return back()->withErrors([
                'email' => 'Account not found.',
            ])->onlyInput('email');
        }

        $otp = CustomerLoginOtp::query()
            ->where('email', $customer->email)
            ->whereNull('consumed_at')
            ->where('expires_at', '>=', now())
            ->orderByDesc('created_at')
            ->first();

        if (!$otp || !hash_equals($otp->code_hash, hash('sha256', trim($validated['otp_code'])))) {
            return back()->withErrors([
                'otp_code' => 'Invalid or expired code.',
            ])->onlyInput('email');
        }

        $otp->update(['consumed_at' => now()]);

        Auth::guard('customer')->login($customer);
        $request->session()->regenerate();
        return redirect()->route('dashboard')->with('success', 'Login successful!');
    }

    public function showPasswordLogin()
    {
        $adminKey = (string) env('ADMIN_PASSWORD_LOGIN_KEY', '');
        abort_if($adminKey === '', 404);

        return view('auth.login-password');
    }

    public function passwordLogin(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'admin_key' => 'required|string',
        ]);

        $adminKey = (string) env('ADMIN_PASSWORD_LOGIN_KEY', '');
        if ($adminKey === '' || !hash_equals($adminKey, (string) $validated['admin_key'])) {
            return back()->withErrors([
                'admin_key' => 'Invalid admin key.',
            ])->onlyInput('email');
        }

        if (Auth::guard('customer')->attempt([
            'email' => $validated['email'],
            'password' => $validated['password'],
        ])) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Login successful!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'Logged out successfully!');
    }
}