<?php

namespace App\Http\Controllers\Central;

use App\Events\Central\MessageSent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MailCentralController extends Controller
{
    public function index()
    {
        return Inertia::render("Central/Mail/MailsCentral");
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            "message" => "required|string|max:255",
        ]);

        // Transmita a mensagem usando o evento MessageSent
        broadcast(new MessageSent(["text" => $validated["message"]]));

        return response()->json(["success" => true]);
    }
}
