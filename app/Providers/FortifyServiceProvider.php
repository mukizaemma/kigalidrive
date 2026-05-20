<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Responses\LoginResponse;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::loginView(fn () => view('auth.login'));
        Fortify::requestPasswordResetLinkView(fn () => view('auth.forgot-password'));
        Fortify::resetPasswordView(fn ($request) => view('auth.reset-password', ['request' => $request]));

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where(Fortify::username(), $request->{Fortify::username()})->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return null;
            }
            if (($user->status ?? 'Active') === 'Inactive') {
                throw ValidationException::withMessages([
                    Fortify::username() => [__('This account has been suspended. Please contact support.')],
                ]);
            }

            $ttlHours = (int) config('registration.verification_ttl_hours', 24);
            if (! $user->exemptFromEmailVerification() && ! $user->hasVerifiedEmail() && $user->created_at && $user->created_at->lt(now()->subHours($ttlHours))) {
                $user->delete();
                throw ValidationException::withMessages([
                    Fortify::username() => [__('This registration expired because the email was not verified in time. Please register again.')],
                ]);
            }

            return $user;
        });
        
        // Use custom login response to check email verification
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
