<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //Gate permite definir una politica de acceso a una ruta
        Gate::define('admin.home', function ($user) {
            return $user->hasRole('Admin');
        });

        //Definir una politica de acceso a la ruta de evaluations donde solo el instructor que creo el curso puede acceder 
        Gate::define('instructor.home', function ($user) {
            return $user->hasRole('Instructor');
        });
    }
}
