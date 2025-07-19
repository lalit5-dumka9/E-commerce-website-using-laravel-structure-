<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Admin;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();



        Gate::define('access-validation', function($user,$permission){
            
            if ($user->role==0) {
               return true;
            }else{
                $permissions = json_decode($user->getRoleDetails->permissions);
                if (in_array($permission,$permissions)) {
                    return true;
                } else {
                    return false;
                }
            }
            
        
        });
    }
}
