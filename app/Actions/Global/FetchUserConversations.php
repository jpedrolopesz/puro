<?php

namespace App\Actions\Global;

use App\Models\{Conversation, User, Admin};

class FetchUserConversations
{
    public function execute($authUser)
    {
        $conversationsQuery = Conversation::query();

        // Verifica se o usuário autenticado é um Admin ou um User
        if ($authUser instanceof Admin) {
            $conversationsQuery->where("admin_id", $authUser->id);
        } elseif ($authUser instanceof User) {
            $conversationsQuery->where("user_id", $authUser->id);
        } else {
            // Se não for nem Admin nem User, retorna uma coleção vazia
            return collect();
        }

        // Carrega as mensagens e os participantes da conversa
        $conversations = $conversationsQuery
            ->with(["messages", "admin", "user"])
            ->get();

        // Formata os resultados
        $formattedConversations = $conversations->map(function (
            $conversation
        ) use ($authUser) {
            $otherParticipant =
                $authUser instanceof Admin
                    ? $conversation->user
                    : $conversation->admin;

            return [
                "id" => (string) $conversation->id,
                "user_id" => $conversation->user_id,
                "admin_id" => $conversation->admin_id,
                "labels" => (object) $conversation->labels,
                "subject" => $conversation->subject,
                "participant" => [
                    "id" => $otherParticipant->id,
                    "name" => $otherParticipant->name,
                    "email" => $otherParticipant->email,
                    "type" =>
                        $otherParticipant instanceof Admin ? "admin" : "user",
                ],
                "messages" => $conversation->messages->map(function ($message) {
                    return [
                        "id" => (string) $message->id,
                        "content" => $message->content,
                        "sender_id" => (string) $message->sender_id,
                        "sender_type" => $message->sender_type,
                        "read" => (bool) $message->read,
                        "date" => $message->date,
                    ];
                }),
                "created_at" => $conversation->created_at,
                "updated_at" => $conversation->updated_at,
            ];
        });

        return $formattedConversations;
    }
}
