<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Show the email verification notice (regular users only).
     */
    public function show(Request $request)
    {
        $user = $request->user();

        if ($user && $user->exemptFromEmailVerification()) {
            $this->ensureAdminEmailMarkedVerified($user);

            return redirect()->route('dashboard');
        }

        return view('auth.verify-email');
    }

    /**
     * Mark the user's email address as verified.
     */
    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('home').'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(route('home').'?verified=1');
    }

    /**
     * Resend the email verification notification.
     */
    public function resend(Request $request)
    {
        $user = $request->user();

        if ($user->exemptFromEmailVerification()) {
            $this->ensureAdminEmailMarkedVerified($user);

            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'message' => 'Your admin account does not require email verification.',
                    'redirect' => route('dashboard'),
                ]);
            }

            return redirect()->route('dashboard')
                ->with('success', 'Your admin account does not require email verification.');
        }

        if ($user->hasVerifiedEmail()) {
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json(['message' => 'Email already verified.'], 400);
            }

            return back()->with('status', 'email-already-verified');
        }

        $user->sendEmailVerificationNotification();

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['message' => 'Verification link sent!']);
        }

        return back()->with('status', 'verification-link-sent');
    }

    protected function ensureAdminEmailMarkedVerified($user): void
    {
        if (! $user->hasVerifiedEmail()) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }
    }
}
