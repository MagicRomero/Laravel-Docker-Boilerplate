<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Laravel\Fortify\Features;
use Laravel\Fortify\Http\Controllers\{
    AuthenticatedSessionController,
    ConfirmablePasswordController,
    ConfirmedPasswordStatusController,
    NewPasswordController,
    PasswordResetLinkController,
    RecoveryCodeController,
    TwoFactorAuthenticatedSessionController,
    TwoFactorAuthenticationController,
    TwoFactorQrCodeController,
    EmailVerificationNotificationController,
    EmailVerificationPromptController,
    PasswordController,
    ProfileInformationController,
    RegisteredUserController,
    VerifyEmailController
};


$limiter = config('fortify.limiters.login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware(array_filter([
        'guest',
        $limiter ? 'throttle:' . $limiter : null,
    ]));

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');


Route::post('/two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'store'])
    ->middleware(['guest']);

// Password Reset...
if (Features::enabled(Features::resetPasswords())) {
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->middleware(['guest'])
        ->name('password.email');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->middleware(['guest'])
        ->name('password.update');
}

Route::middleware(['auth:sanctum', 'language'])->group(function () {

    Route::prefix('user')->group(function () {
        Route::get('current', [UserController::class, 'current'])->name('current-user');

        // Password Confirmation...
        Route::get('confirmed-password-status', [ConfirmedPasswordStatusController::class, 'show'])
            ->name('password.confirmation');

        Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);


        // Two Factor Authentication...
        if (Features::enabled(Features::twoFactorAuthentication())) {

            $twoFactorMiddleware = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')
                ? ['password.confirm']
                : [];

            Route::post('two-factor-authentication', [TwoFactorAuthenticationController::class, 'store'])
                ->middleware($twoFactorMiddleware);

            Route::delete('two-factor-authentication', [TwoFactorAuthenticationController::class, 'destroy'])
                ->middleware($twoFactorMiddleware);

            Route::get('two-factor-qr-code', [TwoFactorQrCodeController::class, 'show'])
                ->middleware($twoFactorMiddleware);

            Route::get('two-factor-recovery-codes', [RecoveryCodeController::class, 'index'])
                ->middleware($twoFactorMiddleware);

            Route::post('two-factor-recovery-codes', [RecoveryCodeController::class, 'store'])
                ->middleware($twoFactorMiddleware);
        }
    });



    /*
    // Registration...


        Route::post('/register', [RegisteredUserController::class, 'store'])
            ->middleware(['guest']);
    }

    // Email Verification...
    if (Features::enabled(Features::emailVerification())) {


        Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
            ->middleware(['auth', 'signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware(['auth', 'throttle:6,1'])
            ->name('verification.send');
    }

    // Profile Information...
    if (Features::enabled(Features::updateProfileInformation())) {
        Route::put('/user/profile-information', [ProfileInformationController::class, 'update'])
            ->middleware(['auth'])
            ->name('user-profile-information.update');
    }

    // Passwords...
    if (Features::enabled(Features::updatePasswords())) {
        Route::put('/user/password', [PasswordController::class, 'update'])
            ->middleware(['auth'])
            ->name('user-password.update');
    }
*/
});
