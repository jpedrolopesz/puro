<?php

namespace App\Http\Controllers\Central;

use App\Events\Central\MailSentEvent;

use App\Events\Central\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Mail;
use App\Models\Message;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;

use Inertia\Inertia;

class MailCentralController extends Controller
{
    public function index()
    {
        $mails = Mail::with(["messages", "sender", "receiver"])->get();

        $mailsWithMessages = $mails->map(function ($mail) {
            $messages = Message::where("mail_id", $mail->id)->get();
            // $users = User::where("id", $mail->sender_id)->get();

            $mail->messages = $messages;
            // $mail->users = $users;
            $mail->labels = is_array($mail->labels)
                ? $mail->labels
                : json_decode($mail->labels, true);

            $mail->id = (string) $mail->id;

            return $mail;
        });

        $tenants = Tenant::with("creator", "users")->get();

        $tenantsWithUsers = $tenants->map(function ($tenant) {
            $users = User::where("tenant_id", $tenant->id)->get();

            $tenant->users = $users;
            $tenant->creator = $tenant->creator;

            return $tenant;
        });

        return Inertia::render("Central/Mail/MailsCentral", [
            "mails" => $mailsWithMessages,
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
        $mail = Mail::create([
            "id" => $request->input("id"),
            "sender_id" => $request->input("sender_id"),
            "receiver_id" => $request->input("receiver_id"),
            "name" => $request->input("name"),
            "email" => $request->input("email"),
            "subject" => $request->input("subject"),
            "text" => $request->input("text"),
            "read" => $request->input("read", false),
            "labels" => json_encode($request->input("labels", [])),
            "date" => $request->input("date"),
        ]);

        Message::create([
            "mail_id" => $mail->id,
            "text" => $request->input("text"),
            "sender_type" => $request->input("sender_type"),
        ]);

        //broadcast(new MailSentEvent($mail))->toOthers();

        return;
    }
}
