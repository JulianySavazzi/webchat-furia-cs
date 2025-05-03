<?php

namespace App\Services;

use App\Models\Message;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class MessageService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param int $id
     * @param array $data
     * @return Collection
     */
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

    /**
     * @param int $id
     * @param array $data
     * @return Message
     */
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

    /**
     * @param int $idTeam
     * @param array $data
     * @return Collection
     */
    public function listTeamMessages(int $idTeam, array $data)
    {
        return Team::query()->with('messages', 'messages.userFrom:id,username', 'users:id,username')
            ->where(['id' => $idTeam])
            ->get();
    }

    /**
     * @param int $idTeam
     * @param array $data
     * @return Message
     */
    public function sendTeamMessage(int $idTeam, array $data)
    {
        $userFrom = auth()->user();
        $team = Team::query()->find($idTeam);
        $userTo = $team->users;

        $message = new Message();
        $message->content = $data['content'];
        $message->userFrom()->associate($userFrom);
        $message->team()->associate($idTeam);

        $message->save();
        return $message;
    }
}
