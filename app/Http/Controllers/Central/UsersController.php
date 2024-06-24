<?php

namespace App\Http\Controllers\Central;

use App\Actions\Central\Users\UserGetAllAction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(["search", "trashed"]);
        $users = UserGetAllAction::run($filters);

        return Inertia::render("Central/Users/UsersCentral", [
            "users" => $users,
        ]);
    }
}
