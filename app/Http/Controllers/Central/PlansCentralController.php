<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlansCentralController extends Controller
{
    public function index()
    {
        return Inertia::render("Central/Plans/PlansCentral");
    }

    public function create()
    {
        return Inertia::render("Central/Plans/Create/PlanCreate");
    }
}
