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
        $params['first-message'] = implode(' . ', FirstMessage::values()) ?? "interagir com outros FURIOSOS";
        $params['bot-options'] = implode(' . ', BotOptionsMessage::values()) ?? "novidades sobre a FURIA";
        switch ($case) {
            case 'welcome-team':
                $message = "Olá " . $params["username"] . " ! Bem vindo(a) a " . $params['team-name'] . " ! Se você quiser saber as novidades da FURIA, é só mandar uma mensagem me marcando: @furia_bot !";
                break;
            case 'welcome-user':
                $message = "Olá " . $params["username"] . " ! Bem vindo(a) ao WEBCHAT FURIA CS! Aqui você pode " . $params['first-message'] . " !";
                break;
            case 'come-back':
                $message = "Bem vindo(a) de volta " . $params["username"] . " !";
                break;
            default:
                $message = "Olá " . $params["username"] . ", sou o FURIA Bot, owner da team_furia_cs, posso te ajudar com: "
                    . $params['bot-options'] . " . Basta digitar a opção desejada!";
                break;
        }
        return $message;
    }

    /**
     * @param string $case
     * @param array|null $params
     * @return string
     */
    public static function templateBotReplyMessages(string $case, ?array $params): string
    {
        switch ($case) {
            case '!YTFCS':
                $message = "Para encontrar o que deseja, é só clicar nesse link: " . BotOptionsMessage::ytFuriaCs->value;
                break;
            case '!FURGG':
                $message = "Para encontrar o que deseja, é só clicar nesse link: " . BotOptionsMessage::furiaGg->value;
                break;
            case '!FURIX':
                $message = "Para encontrar o que deseja, é só clicar nesse link: " . BotOptionsMessage::twitter->value;
                break;
            case '!MATCH':
                $message = "Para encontrar o que deseja, é só clicar nesse link: " . BotOptionsMessage::matches->value;
                break;
            case '!EVENT':
                $message = "Para encontrar o que deseja, é só clicar nesse link: " . BotOptionsMessage::events->value;
                break;
            case '!FNEWS':
                $message = "Para encontrar o que deseja, é só clicar nesse link: " . BotOptionsMessage::teamNews->value;
                break;
            case '!STATS':
                $message = "Para encontrar o que deseja, é só clicar nesse link: " . BotOptionsMessage::teamNews->value;
                break;
            default:
                $message = "Olá, sou o FURIA Bot, se você quer assistir nossos videos digite: !YTFCS" .
                    " ,\n se quer saber sobre nossos produtos digite: !FURGG" .
                    " ,\n se quiser acessar nosso X (antigo twitter) digite: !FURIX" .
                    " ,\n se quiser saber sobre as próximas partidas digite: !MATCH" .
                    " ,\n se quiser saber sobre os eventos digite: !EVENT" .
                    " ,\n se quiser saber sobre aas novidades da furia cs digite: !FNEWS" .
                    " , se quiser saber sobre stats da furia cs digite: !STATS";
        }
        return $message;
    }

    /**
     * @param string $case
     * @param array|null $params
     * @return string
     */
    public static function templateBotReplyTeamMessages(string $case, ?array $params): string
    {
        switch ($case) {
            case '!YTFCS':
                $message = "Para encontrar o que deseja, é só clicar nesse link: " . BotOptionsMessage::ytFuriaCs->value;
                break;
            case '!FURGG':
                $message = "Para encontrar o que deseja, é só clicar nesse link: " . BotOptionsMessage::furiaGg->value;
                break;
            case '!FURIX':
                $message = "Para encontrar o que deseja, é só clicar nesse link: " . BotOptionsMessage::twitter->value;
                break;
            case '!MATCH':
                $message = "Para encontrar o que deseja, é só clicar nesse link: " . BotOptionsMessage::matches->value;
                break;
            case '!EVENT':
                $message = "Para encontrar o que deseja, é só clicar nesse link: " . BotOptionsMessage::events->value;
                break;
            case '!FNEWS':
                $message = "Para encontrar o que deseja, é só clicar nesse link: " . BotOptionsMessage::teamNews->value;
                break;
            case '!STATS':
                $message = "Para encontrar o que deseja, é só clicar nesse link: " . BotOptionsMessage::teamNews->value;
                break;
            default:
                $message = "Olá, sou o FURIA Bot, se você quer assistir nossos videos é só clicar nesse link: " . BotOptionsMessage::ytFuriaCs->value .
                    " ,\n se quer saber sobre nossos produtos é só clicar nesse link: " . BotOptionsMessage::furiaGg->value .
                    " ,\n se quiser acessar nosso X (antigo twitter) é só clicar nesse link: " . BotOptionsMessage::twitter->value .
                    " ,\n se quiser saber sobre as próximas partidas é só clicar nesse link: " . BotOptionsMessage::matches->value .
                    " ,\n se quiser saber sobre os eventos é só clicar nesse link: " . BotOptionsMessage::events->value .
                    " ,\n se quiser saber sobre as novidades da furia cs é só clicar nesse link: " . BotOptionsMessage::teamNews->value .
                    " , se quiser saber sobre stats da furia cs é só clicar nesse link: " . BotOptionsMessage::teamNews->value;
        }
        return strtoupper($message);
    }
}
