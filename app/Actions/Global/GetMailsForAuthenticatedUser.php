<?php

namespace App\Actions\Global;

use App\Models\Mail;
use App\Models\User;
use App\Models\Admin;

class GetMailsForAuthenticatedUser
{
    public function execute($authUser)
    {
        $mailsQuery = Mail::query();

        $mailsQuery->where(function ($query) use ($authUser) {
            $query
                ->where(function ($subQuery) use ($authUser) {
                    $subQuery
                        ->where("sender_id", $authUser->id)
                        ->where("sender_type", get_class($authUser));
                })
                ->orWhere(function ($subQuery) use ($authUser) {
                    $subQuery
                        ->where("receiver_id", $authUser->id)
                        ->where("receiver_type", get_class($authUser));
                });
        });

        // Carrega mensagens, remetente e destinatário
        $mails = $mailsQuery->with(["messages", "sender"])->get();

        // Formata os resultados
        $mailsWithMessages = $mails->map(function ($mail) {
            $mail->labels = is_array($mail->labels)
                ? $mail->labels
                : json_decode($mail->labels, true);

            $mail->id = (string) $mail->id;

            return $mail;
        });

        return $mailsWithMessages;
    }
}
