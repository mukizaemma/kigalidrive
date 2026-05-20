<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Fortify;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = $request->user();

        if (($user->status ?? 'Active') === 'Inactive') {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Your account has been suspended. Please contact support if you believe this is a mistake.');
        }

        if ($user->exemptFromEmailVerification() && ! $user->hasVerifiedEmail()) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        // Regular users must verify email; admins and super admin do not
        if (! $user->exemptFromEmailVerification() && ! $user->hasVerifiedEmail()) {
            // Don't logout - keep user logged in but redirect to verification notice
            // This allows them to resend verification email while authenticated
            
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'message' => 'Please verify your email address. We have sent you a verification link.',
                    'verified' => false,
                    'redirect' => route('verification.notice'),
                    'resend_url' => route('verification.send')
                ], 200); // Changed to 200 so AJAX doesn't treat it as error
            }

            return redirect()->route('verification.notice')
                ->with('status', 'verification-link-sent')
                ->with('message', 'Please verify your email address before accessing the system.');
        }

        // Email is verified — admins (role 1 or 2) go to dashboard
        if (in_array((int) ($user->role ?? 0), [1, 2], true)) {
            $adminRedirectUrl = route('dashboard');

            $redirect = $request->wantsJson()
                ? response()->json([
                    'redirect' => $adminRedirectUrl,
                    'verified' => true,
                ])
                : redirect($adminRedirectUrl);

            if ($user->isSuperAdmin() && ! $request->wantsJson()) {
                return $redirect->with(
                    'success',
                    'Welcome, Super Admin. You have full access to the admin panel — no email verification required.'
                );
            }

            return $redirect;
        }
        
        // Regular users - check if there's a redirect_after_login parameter (from booking modal)
        $redirectUrl = $request->input('redirect_after_login');
        if (!$redirectUrl) {
            $redirectUrl = $request->session()->get('url.intended', route('home'));
        }
        
        return $request->wantsJson()
                    ? response()->json([
                        'redirect' => $redirectUrl,
                        'verified' => true
                    ])
                    : redirect($redirectUrl);
    }
}

