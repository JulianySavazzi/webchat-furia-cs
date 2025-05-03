<?php

namespace App\Services;

use App\Models\Message;
use App\Models\Team;
use App\Models\User;

class MessageService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function listUsersMessages(int $id, array $data)
    {
        $userFrom = auth()->user();
        $userTo = User::query()->find($id);

        return Message::query()->with('userFrom', 'userTo')
            ->where('team_id', null)
            ->where(['user_from' => $userFrom->id, 'user_to' => $userTo->id])
            ->orWhere(['user_from' => $userTo->id, 'user_to' => $userFrom->id])
            ->orderBy('created_at', 'asc')->get();

    }

    public function sendMessage(int $id, array $data)
    {
        $userFrom = auth()->user();
        $userTo = User::query()->find($id);
        $message = new Message();
        $message->content = $data['content'];
        $message->userFrom()->associate($userFrom);
        $message->userTo()->associate($userTo);
        $message->save();

        //TODO event dispacther broadcast reverb

        return $message;
    }

    public function listTeamMessages(int $idTeam, array $data)
    {
//        return Message::query()->with('userFrom', 'userTo');
        return Team::query()->with('messages', 'messages.userFrom:id,username', 'users:id,username')
            ->where(['id' => $idTeam])
            ->get();
    }

    public function sendTeamMessage(int $idTeam, array $data)
    {
        $userFrom = auth()->user();
        $team = Team::query()->find($idTeam);
        $userTo = $team->users;

        $message = new Message();
        $message->content = $data['content'];
        $message->userFrom()->associate($userFrom);
        $message->team()->associate($idTeam);
//        $message->userTo()->associate($userTo);
//        $team->users()->sync($userTo);

//        foreach ($userTo as $user) {
//            $message->userTo()->associate($user);
//        }

        $message->save();
        return $message;
    }
}
