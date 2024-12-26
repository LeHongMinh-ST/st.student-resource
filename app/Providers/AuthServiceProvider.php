<?php

declare(strict_types=1);

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\User;
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

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Check if the 'permissions' table exists in the database
        $rulePermissions = config('permissions');

        // Iterate through each permission
        foreach ($rulePermissions as $ruleGroup => $permissionActionGroups) {
            foreach ($permissionActionGroups as $actionGroup => $permissions) {
                foreach ($permissions as $action => $permission) {
                    Gate::define($ruleGroup . '.' . $actionGroup . '.' . $action, function ($user) use ($permission) {
                        // Check if the user's role ID is in the list of roles for the permission
                        // or if the user is a super admin
                        return in_array($user->role, $permission) || ($user?->is_super_admin && $user instanceof User);
                    });
                }
            }
        }

    }
}
