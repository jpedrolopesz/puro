<?php

namespace App\Actions\Global;

use App\Models\{Conversation, User, Admin};

class FetchUserConversations
{
    public function execute($authUser)
    {
        $conversationsQuery = Conversation::query();

        // Filtra as conversas com base no usuário autenticado
        $conversationsQuery->where(function ($query) use ($authUser) {
            $query
                ->where(function ($q) use ($authUser) {
                    $q->where("initiator_id", $authUser->id)->where(
                        "initiator_type",
                        get_class($authUser)
                    );
                })
                ->orWhere(function ($q) use ($authUser) {
                    $q->where("recipient_id", $authUser->id)->where(
                        "recipient_type",
                        get_class($authUser)
                    );
                });
        });

        $conversations = $conversationsQuery->with(["messages"])->get();

        $formattedConversations = $conversations->map(function (
            $conversation
        ) use ($authUser) {
            $otherParticipant =
                $conversation->initiator_id == $authUser->id
                    ? [
                        "id" => $conversation->recipient_id,
                        "type" => $conversation->recipient_type,
                    ]
                    : [
                        "id" => $conversation->initiator_id,
                        "type" => $conversation->initiator_type,
                    ];

            $otherParticipantModel = $otherParticipant["type"]::find(
                $otherParticipant["id"]
            );

            return [
                "id" => (string) $conversation->id,
                "initiator_id" => $conversation->initiator_id,
                "initiator_type" => $conversation->initiator_type,
                "recipient_id" => $conversation->recipient_id,
                "recipient_type" => $conversation->recipient_type,
                "labels" => (object) $conversation->labels,
                "subject" => $conversation->subject,
                "participant" => [
                    "id" => $otherParticipantModel->id,
                    "name" => $otherParticipantModel->name,
                    "email" => $otherParticipantModel->email,
                    "type" =>
                        $otherParticipant["type"] === Admin::class
                            ? "admin"
                            : "user",
                ],
                "messages" => $conversation->messages->map(function ($message) {
                    return [
                        "id" => (string) $message->id,
                        "content" => $message->content,
                        "sender_id" => (string) $message->sender_id,
                        "sender_type" => $message->sender_type,
                        "read" => (bool) $message->read,
                        "created_at" => $message->created_at,
                    ];
                }),
                "created_at" => $conversation->created_at,
                "updated_at" => $conversation->updated_at,
            ];
        });

        return $formattedConversations;
    }
}
