<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MailCentralController extends Controller
{
    public function index()
    {
        return Inertia::render("Central/Mail/MailsCentral");
    }
}
