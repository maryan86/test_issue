<?php

namespace App\Http\Controllers;

use App\Http\Requests\Rules\ResetPassword;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Auth\PasswordBroker;

class PasswordController extends Controller
{
    public function __construct(private UserService $service)
    {
        //
    }

    public function sendResetLinkEmail(Request $request): JsonResponse
    {
        $this->broker()->sendResetLink($request->only('email'));

        return response()->json(['success' => true]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function reset(Request $request): JsonResponse
    {
        $this->validate($request, ResetPassword::rules());

        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        if ($response == Password::PASSWORD_RESET) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    /**
     * Get the password reset credentials from the request.
     *
     * @param Request $request
     *
     * @return array
     */
    protected function credentials(Request $request): array
    {
        return $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );
    }

    protected function resetPassword($user, $password)
    {
        $this->service->update($user, ['password' => Hash::make($password)]);

        event(new PasswordReset($user));

        return $user;
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return PasswordBroker
     */
    protected function broker(): PasswordBroker
    {
        return Password::broker('users');
    }

}
