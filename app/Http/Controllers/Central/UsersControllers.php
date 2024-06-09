<?php

namespace App\Http\Controllers;

use App\Actions\Central\Users\UserGetAllAction;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(["search", "trashed"]);
        $users = UserGetAllAction::run($filters);

        return view("users.index", compact("users"));
    }
}
