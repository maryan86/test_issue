<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Lumen\Http\Request;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function (Request $request) {
            if ($this->checkHeaderAuthorization($request)) {
                $key = explode(' ', $request->header('Authorization'));
                $user = User::where('api_token', $key[1])->first();

                if (!empty($user)) {
                    $request->request->add(['user_id' => $user->id]);
                    return true;
                }
            }
        });
    }

    private function checkHeaderAuthorization(Request $request): bool
    {
        return $request->header('Authorization') && Str::startsWith($request->header('Authorization'), 'Bearer ');
    }
}
