<?php

namespace App\Helper;

use App\Helper\Enums\BotOptionsMessage;
use App\Helper\Enums\FirstMessage;

class MessageHelper
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Escrever o content da mensagem que será enviada pelo FURIA BOT
     *
     * @param string $case - welcome-team, welcome-user, come-back (default sao as bot-options)
     * @param array|null $params - team-name, username (first-message e bot-options preenchidos pelos ENUMs)
     * @return string
     */
    public static function templateMessages(string $case, ?array $params): string
    {
        $params['team-name'] = $params['team-name'] ?? "TEAM";
        $params['username'] = $params['username'] ?? "FURIOSO(A)";
        $params['first-message'] = implode(', ', FirstMessage::values()) ?? "interagir com outros FURIOSOS";
        $params['bot-options'] = implode(',' , BotOptionsMessage::values()) ?? "novidades sobre a FURIA";
        switch ($case) {
            case 'welcome-team':
                $message = "Olá ".$params["username"]." !. Bem vindo(a) a ".$params['team-name']." !";
                break;
            case 'welcome-user':
                $message = "Olá ".$params["username"]." !. Bem vindo(a) ao WEBCHAT FURIA CS! Aqui você pode ".$params['first-message']." !";
                break;
            case 'come-back':
                $message = "Bem vindo(a) de volta ".$params["username"]." !";
                break;
            default:
                $message = "Olá ".$params["username"].", sou o FURIA Bot, owner da team_furia_cs, posso te ajudar com: "
                .$params['bot-options']." . Basta digitar a opção desejada!";
                break;
        }
        return $message;
    }
}
