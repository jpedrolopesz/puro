<?php

namespace App\Http\Controllers\Tenant;

use App\Actions\Global\GetMailsForAuthenticatedUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MailMessageController extends Controller
{
    protected $getMailsForAuthenticatedUser;

    public function __construct(
        GetMailsForAuthenticatedUser $getMailsForAuthenticatedUser
    ) {
        $this->getMailsForAuthenticatedUser = $getMailsForAuthenticatedUser;
    }
    public function index()
    {
        $authUser = auth()->user();
        $mailsWithMessages = $this->getMailsForAuthenticatedUser->execute(
            $authUser
        );

        return Inertia::render("Tenant/Mail/MailsTenant", [
            "mails" => $mailsWithMessages,
        ]);
    }
}
