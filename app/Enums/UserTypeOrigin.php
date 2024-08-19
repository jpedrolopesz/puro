<?php

namespace App\Enums;

enum UserTypeOrigin: string
{
    case CENTRAL_ADMIN = "central_admin";
    case TENANT_USER = "tenant_user";
}
