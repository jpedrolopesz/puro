<?php

namespace App\Services;

use Stancl\Tenancy\Contracts\TenancyBootstrapper;
use Stancl\Tenancy\Contracts\Tenant;

class AuthGuardBootstrapper implements TenancyBootstrapper
{
    public function bootstrap(Tenant $tenant)
    {
        config(["auth.guard" => "web"]);
        config(["auth.passwords" => "users"]);
    }

    public function revert()
    {
        config(["auth.guard" => "admin"]);
        config(["auth.passwords" => "admins"]);
    }
}
