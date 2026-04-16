<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\PasswordOtpToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class OtpPasswordController extends Controller
{
    // ── FORGOT PASSWORD FLOW (guest) ──────────────────────────

    public function showForgotForm()
    {
        return view('auth.otp-forgot');
    }

    public function sendForgotOtp(Request $request)
    {
        $request->validate(['email' => ['required', 'email', 'exists:users,email']]);

        $key = 'otp-forgot:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors(['email' => "Too many attempts. Try again in {$seconds}s."]);
        }
        RateLimiter::hit($key, 60);

        $otp = PasswordOtpToken::generate($request->email);
        Mail::to($request->email)->send(new OtpMail($otp, 'reset'));

        return redirect()->route('password.otp.verify')
            ->with('otp_email', $request->email)
            ->with('otp_context', 'forgot');
    }

    public function showForgotVerify(Request $request)
    {
        $email = session('otp_email') ?? $request->query('email');
        if (!$email) return redirect()->route('password.otp.request');

        return view('auth.otp-verify', [
            'email'   => $email,
            'context' => 'forgot',
        ]);
    }

    public function verifyForgotOtp(Request $request)
    {
        $request->validate([
            'email'                 => ['required', 'email', 'exists:users,email'],
            'otp'                   => ['required', 'digits:6'],
            'password'              => ['required', 'min:8', 'confirmed'],
        ]);

        if (!PasswordOtpToken::verify($request->email, $request->otp)) {
            return back()->withInput()->withErrors(['otp' => 'Invalid or expired code.']);
        }

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')
            ->with('status', 'Password reset successfully. Please log in.');
    }

    // ── PROFILE CHANGE PASSWORD FLOW (auth) ──────────────────

    public function sendProfileOtp(Request $request)
    {
        $email = $request->user()->email;

        $key = 'otp-profile:' . $request->user()->id;
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors(['otp' => "Too many attempts. Try again in {$seconds}s."]);
        }
        RateLimiter::hit($key, 60);

        $otp = PasswordOtpToken::generate($email);
        Mail::to($email)->send(new OtpMail($otp, 'profile'));

        return redirect()->route('profile.password.verify')
            ->with('otp_sent', true);
    }

    public function showProfileVerify()
    {
        return view('profile.otp-verify');
    }

    public function verifyProfileOtp(Request $request)
    {
        $request->validate([
            'otp'                   => ['required', 'digits:6'],
            'password'              => ['required', 'min:8', 'confirmed'],
        ]);

        $email = $request->user()->email;

        if (!PasswordOtpToken::verify($email, $request->otp)) {
            return back()->withErrors(['otp' => 'Invalid or expired code.']);
        }

        $request->user()->update(['password' => Hash::make($request->password)]);

        return redirect()->route('profile.edit')
            ->with('status', 'password-updated');
    }
}
