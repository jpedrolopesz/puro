<?php

namespace App\Enums\Central;

enum AdminRole: string
{
    case SuperAdmin = "super_admin";
    case Moderator = "moderator";
}
