<?php

namespace App\Http\Controllers\Central;

use App\Events\Central\MailSentEvent;
use Illuminate\Support\Facades\Validator;

use App\Events\Central\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function send(Request $request)
    {
        // Criação do novo e-mail
        $mail = Mail::create([
            "sender_id" => Auth::id(),
            "receiver_id" => $request->input("receiver_id"),
            "name" => $request->input("name"),
            "email" => $request->input("email"),
            "subject" => $request->input("subject"),
            "text" => $request->input("text"),
            "read" => $request->input("read"),
            "labels" => json_encode($request->input("labels")),
        ]);

        broadcast(new MailSentEvent($mail))->toOthers();

        return response()->json($mail);
    }
}
