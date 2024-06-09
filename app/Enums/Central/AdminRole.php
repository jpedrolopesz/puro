<?php

namespace App\Enums;

enum AdminRole: string
{
    case SuperAdmin = "super_admin";
    case Moderator = "moderator";
}
