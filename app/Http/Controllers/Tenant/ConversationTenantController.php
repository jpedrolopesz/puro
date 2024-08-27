<?php

namespace App\Http\Controllers\Tenant;

use App\Actions\Global\FetchUserConversations;
use App\Http\Controllers\Controller;
use App\Models\{Tenant, User};
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ConversationTenantController extends Controller
{
    protected $fetchUserConversations;

    public function __construct(FetchUserConversations $fetchUserConversations)
    {
        $this->fetchUserConversations = $fetchUserConversations;
    }
    public function index()
    {
        $authUser = Auth::guard("web")->user();
        $conversationWithMessages = $this->fetchUserConversations->execute(
            $authUser
        );

        //dd(Admin::all());
        return Inertia::render("Tenant/Conversation/Tenant", [
            "conversations" => $conversationWithMessages,
            "tenantsWithUsers" => Admin::all(),
        ]);
    }
}
