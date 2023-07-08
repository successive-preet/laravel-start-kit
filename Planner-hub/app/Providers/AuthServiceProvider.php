<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::hashClientSecrets();
     
        Passport::tokensExpireIn(now()->addDays(config('passport.token_expires_in')));
        Passport::refreshTokensExpireIn(now()->addDays(config('passport.refresh_token_expires_in')));
        Passport::personalAccessTokensExpireIn(now()->addDays(config('passport.personal_access_token_expires_in')));
    }
}
