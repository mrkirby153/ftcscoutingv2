<?php

namespace App\Providers;

use App\Models\Surveys\Response;
use App\Models\Surveys\Survey;
use App\Models\Team;
use App\Policies\ResponsePolicy;
use App\Policies\SurveyPolicy;
use App\Policies\TeamPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Response::class => ResponsePolicy::class,
        Survey::class => SurveyPolicy::class,
        Team::class => TeamPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        \Laravel\Passport\Passport::routes();
    }
}
