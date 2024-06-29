<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardCentralController extends Controller
{
    public function index()
    {
        return Inertia::render("Central/Dashboard/DashboardCentral", [
            "admin" => Auth::guard("admin")->user(),
        ]);
    }
}
