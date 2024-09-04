<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class ProductsBuilderCentralController extends Controller
{
    public function index()
    {
        return Inertia::render("Central/Products/Builder/ProductsBuilder");
    }
}
