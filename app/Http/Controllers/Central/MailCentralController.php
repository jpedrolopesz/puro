<?php

namespace App\Http\Controllers\Central;

use App\Events\Central\MailSentEvent;

use App\Events\Central\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Mail;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MailCentralController extends Controller
{
    public function index()
    {
        $mails = Mail::all();

        foreach ($mails as $mail) {
            $mail->labels = is_array($mail->labels)
                ? $mail->labels
                : json_decode($mail->labels, true);

            $mail->id = (string) $mail->id;
        }

        $tenants = Tenant::with("creator")->get();
        $tenantsWithUsers = $tenants->map(function ($tenant) {
            $users = User::where("tenant_id", $tenant->id)->get();
            $tenant->users = $users;
            $tenant->creator = $tenant->creator;

            return $tenant;
        });

        return Inertia::render("Central/Mail/MailsCentral", [
            "mails" => $mails,
            "tenantsWithUsers" => $tenantsWithUsers,
        ]);
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
