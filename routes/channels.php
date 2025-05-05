<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('user.chat.{userId}', function (User $currentUser, int $userId) {
    /*
     * Autoriza o acesso se:
     * 1. O usuário atual é o destinatário (receiverId)
     * OU
     * 2. O usuário atual é o remetente (senderId)
     *
     * Isso permite que ambos os lados da conversa recebam as mensagens
     * em tempo real através do mesmo canal
     */
    return $currentUser->id === $userId || $currentUser->id === 1;
});

Broadcast::channel('team.chat.{teamId}', function (User $user, int $teamId) {
    /*
     * Todos os usuários autenticados têm acesso ao time principal (ID 1) + o bot (ID 1) também tem acesso
     * */
    return [
        'id' => $user->id,
        'name' => $user->name,
        'username' => $user->username,
        'is_bot' => $user->id === 1
    ];
});
