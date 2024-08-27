<?php

namespace App\Http\Controllers\Central;

use App\Events\MailSentEvent;

use App\Actions\Global\FetchUserConversations;
use App\Events\Central\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Mail;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MailCentralController extends Controller
{
    protected $fetchUserConversations;

    public function __construct(FetchUserConversations $fetchUserConversations)
    {
        $this->fetchUserConversations = $fetchUserConversations;
    }
    public function index()
    {
        $authUser = Auth::guard("admin")->user();
        $conversationWithMessages = $this->fetchUserConversations->execute(
            $authUser
        );

        //dd($conversationWithMessages);
        $tenants = Tenant::with("creator", "users")->get();

        $tenantsWithUsers = $tenants->map(function ($tenant) {
            $users = User::where("tenant_id", $tenant->id)->get();

            $tenant->users = $users;
            $tenant->creator = $tenant->creator;

            return $tenant;
        });

        $tenants = Tenant::with("creator", "users")->get();

        $tenantsWithUsers = $tenants->map(function ($tenant) {
            $users = User::where("tenant_id", $tenant->id)->get();

            $tenant->users = $users;
            $tenant->creator = $tenant->creator;

            return $tenant;
        });

        return Inertia::render("Central/Mail/MailsCentral", [
            "conversations" => $conversationWithMessages,
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
        dd($request->all());
        // Recupera os dados da requisição
        $senderId = $request->input("sender_id");
        $receiverId = $request->input("receiver_id");
        $mailId = $request->input("id");
        $isNewMail = !$mailId; // Verifica se é um novo e-mail

        $sender = Auth::guard("admin")->check()
            ? Admin::find($senderId)
            : User::find($senderId);
        $receiver = User::find($receiverId) ?? Admin::find($receiverId);

        if ($isNewMail) {
            // Cria um novo e-mail
            $mail = Mail::create([
                "id" => $request->input("id"),
                "sender_id" => $senderId,
                "sender_type" => get_class($sender),
                "receiver_id" => $receiverId,
                "receiver_type" => get_class($receiver),
                "name" => $request->input("name"),
                "email" => $request->input("email"),
                "subject" => $request->input("subject"),
                "read" => $request->input("read", false),
                "labels" => json_encode($request->input("labels", [])),
                "date" => $request->input("date"),
            ]);
        } else {
            // Recupera o e-mail existente
            $mail = Mail::findOrFail($mailId);
        }

        // Adiciona as mensagens ao e-mail
        if ($request->has("messages")) {
            foreach ($request->input("messages") as $messageData) {
                $mail->messages()->create([
                    "mail_id" => $mail->id,
                    "sender_id" => $senderId,
                    "sender_type" => get_class($sender),
                    "receiver_id" => $receiverId,
                    "receiver_type" => get_class($receiver),
                    "text" => $messageData["text"],
                    "date" => $messageData["date"],
                ]);
            }
        }

        // Dispara um evento de mensagem enviada
        broadcast(new MailSentEvent($mail))->toOthers();

        return response()->json(["status" => "success"]);
    }
}
