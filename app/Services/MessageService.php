<?php

namespace App\Services;

use App\Helper\MessageHelper;
use App\Models\Interaction;
use App\Models\Message;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class MessageService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected readonly UserService $userService
    )
    {
    }

    /**
     * o user from sempre sera o usuario logado ou o bot
     *
     * @param int $id
     * @param array $data
     * @return Collection
     */
    public function listUsersMessages(int $id, array $data)
    {
        $userFrom = auth()->user() ?? 1;
        $userTo = User::query()->find($id);

        return Message::query()->with('userFrom', 'userTo')
            ->where('team_id', null)
            ->where(['user_from' => $userFrom->id, 'user_to' => $userTo->id])
            ->orWhere(['user_from' => $userTo->id, 'user_to' => $userFrom->id])
            ->orderBy('created_at', 'asc')->get();

    }

    /**
     * o userFrom sempre sera o usuario logado ou o bot;
     * verificar quando o userTo for o bot, pra ele enviar uma resposta;
     *
     * @param int $id
     * @param array $data
     * @return Message
     */
    public function sendMessage(int $id, array $data)
    {
        $userFrom = auth()->user() ?? 1;
        $userTo = User::query()->find($id);
        $message = new Message();
        $message->content = $data['content'];
        $message->userFrom()->associate($userFrom);
        $message->userTo()->associate($userTo);
        $message->save();

        if($message->user_to == 1) {
            $data['case'] = $data['case'] ?? $message->content;
            $data['to'] = 'user';
            $this->botReplyMessage($message->user_from, $data);
        }

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
     * o user from sempre sera o usuario logado ou o bot
     *
     * @param int $idTeam
     * @param array $data
     * @return Message
     */
    public function sendTeamMessage(int $idTeam, array $data)
    {
        $userFrom = auth()->user() ?? 1;
        $team = Team::query()->find($idTeam);
        $userTo = $team->users;

        $message = new Message();
        $message->content = $data['content'];
        $message->userFrom()->associate($userFrom);
        $message->team()->associate($idTeam);

        if(Str::contains($message->content, '@furia_bot')) {
            $data['case'] = $data['case'] ?? $message->content;
            $data['to'] = 'team';
            $data['message'] = MessageHelper::templateBotReplyTeamMessages($data['case'], $data) ?? '';
            $this->botReplyMessage($idTeam, $data);
        }

        $message->save();
        return $message;
    }

    /**
     * sempre que um user enviar uma mensagem para o bot, ele vai responder;
     * userFrom sempre sera o bot nesse caso;
     *
     * @param int $userTo
     * @param array $data
     * @return void
     */
    public function botReplyMessage(int $userTo, array $data)
    {
        $message = $data['message'] ?? MessageHelper::templateBotReplyMessages($data['case'], $data);
        $this->sendMessageByBot($data['to'], $message, $userTo);
    }

    /**
     * Utilizado pelo furia bot para enviar mensagens aos usuarios no privado ou na team
     *
     * @param string $case = se a mensagem sera enviada para um user ou team (default user)
     * @param string $content = conteudo da mensagem
     * @param int|null $id = pode passar o id do usuario especifico para enviar uma mensagem
     * @return void
     */
    public function sendMessageByBot(string $case, string $content, ?int $id)
    {
        $data['content'] = $content;
        switch ($case) {
            case 'team':
                $this->sendTeamMessage(1, $data);
                break;
            case 'user':
            default:
                $this->sendMessage($id, $data);
                break;
        }
    }

    /**
     * enviar mensagem de bem vindo de volta, ou outras mensagens que dependem de login e logout interactions
     *
     * @param array $data
     * @return void
     */
    public function sendComeBackMessageByBot(array $data)
    {
        $data['id'] = $data['id'] ?? auth()->user()->id;
        $hasLogout = $this->userService->getLoginUserInteractions(['user_id' => $data['id']]);
        if ($hasLogout) {
            $this->sendMessageByBot('user', $data['content'], $data['id']);
        }
    }

#TODO interactions services

    /**
     * salvar quando o usuario fez login e logout
     *
     * @param array $data
     * @return void
     */
    public function saveLoginInteractions(array $data)
    {
        $now = Carbon::now();
        if (isset($data['user_id'])) {
            if (isset($data['method_name'])) {
                Interaction::query()->create([
                    'user_id' => $data['user_id'],
                    'data' => [$data['method_name'] => [$now]],
                    'furia_bot_id' => null,
                    'message_id' => null,
                ]);
            }
        }
    }

    /**
     * verifica o message->content quando o user envia uma mensagem para o furia_bot;
     * salvar paretes das mensagens com dados sobre a FURIA (matches, stats), sobre CS ou sobre Steam;
     *
     * @param array $data
     * @return void
     */
    public function saveInteractionWithBot(array $data)
    {
        $messages = Message::query()->select('user_from', 'content')
            ->where('user_to', 1)
            ->get();

        foreach ($messages->toArray() as $message) {
            $sanitized = preg_replace('/[^a-zA-Z0-9]/', '', $message['content']);
            if (Str::contains(strtolower($sanitized), 'furiacs') || Str::contains(strtolower($sanitized), 'cs')) {
                Interaction::query()->create([
                    'user_id' => $message['user_from'],
                    'data' => [
                        'furia_cs' => $message['content']
                    ],
                    'furia_bot_id' => $message['user_to'],
                    'message_id' => $message['id'],
                ]);
            } elseif (Str::contains(strtolower($sanitized), 'furiagg') || Str::contains(strtolower($sanitized), 'loja')) {
                Interaction::query()->create([
                    'user_id' => $message['user_from'],
                    'data' => [
                        'furia_gg' => $message['content']
                    ],
                    'furia_bot_id' => $message['user_to'],
                    'message_id' => $message['id'],
                ]);
            } elseif (Str::contains(strtolower($sanitized), 'steam')) {
                Interaction::query()->create([
                    'user_id' => $message['user_from'],
                    'data' => [
                        'steam' => $message['content']
                    ],
                    'furia_bot_id' => $message['user_to'],
                    'message_id' => $message['id'],
                ]);
            } elseif (Str::contains(strtolower($sanitized), 'games') || Str::contains(strtolower($sanitized), 'jogo')) {
                Interaction::query()->create([
                    'user_id' => $message['user_from'],
                    'data' => [
                        'games' => $message['content']
                    ],
                    'furia_bot_id' => $message['user_to'],
                    'message_id' => $message['id'],
                ]);
            } elseif (Str::contains(strtolower($sanitized), 'matche') || Str::contains(strtolower($sanitized), 'partida')) {
                Interaction::query()->create([
                    'user_id' => $message['user_from'],
                    'data' => [
                        'matches' => $message['content']
                    ],
                    'furia_bot_id' => $message['user_to'],
                    'message_id' => $message['id'],
                ]);
            }
        }
    }
}
