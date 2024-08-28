<?php

namespace App\Http\Controllers\Central;

use App\Actions\Global\FetchUserConversations;
use App\Events\NewMessageEvent;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\{Message, Tenant, User, Admin};

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ConversationCentralController extends Controller
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

        $tenants = Tenant::with("creator", "users")->get();
        $admins = Admin::all();

        $tenantsWithUsers = $tenants->map(function ($tenant) use ($admins) {
            $users = User::where("tenant_id", $tenant->id)->get();

            $tenant->users = $users;
            $tenant->creator = $tenant->creator;

            return $tenant;
        });

        $data = [
            "tenants" => $tenantsWithUsers,
            "admins" => $admins,
        ];

        return Inertia::render("Central/Conversation/Central", [
            "conversations" => $conversationWithMessages,
            "conversationParticipants" => $data,
        ]);
    }

    public function createCoversation(Request $request)
    {
        $request->validate([
            "recipient_type" => ["required", 'regex:/^(USR|ADM)-\d{5}$/'],
        ]);

        $recipientData = $request->input("recipient_type");
        $recipientTypePrefix = substr($recipientData, 0, 3);

        $recipientTypeMap = [
            "USR" => "App\Models\User",
            "ADM" => "App\Models\Admin",
        ];

        $recipientTypeClass = $recipientTypeMap[$recipientTypePrefix];
        $sender = $request->user();

        $conversation = Conversation::create([
            "id" => $request->input("id"),
            "initiator_id" => $request->input("initiator_id"),
            "initiator_type" => get_class($sender),
            "recipient_id" => $request->input("recipient_id"),
            "recipient_type" => $recipientTypeClass,
            "labels" => json_encode($request->input("labels")),
            "subject" => $request->input("subject"),
        ]);

        $message = Message::create([
            "conversation_id" => $request->input("id"),
            "sender_id" => $sender->id,
            "sender_type" => get_class($sender),
            "content" => $request->input("content"),
        ]);

        return;
    }

    public function sendMessage(Request $request)
    {
        // dd($request->all());

        $sender = $request->user();

        //dd($sender->all());

        $message = Message::create([
            "conversation_id" => $request->input("conversation_id"),
            "sender_id" => $sender->id,
            "sender_type" => get_class($sender),
            "content" => $request->input("content"),
            "read" => $request->input("read"),
        ]);

        event(new NewMessageEvent($message));

        return;
    }
}
