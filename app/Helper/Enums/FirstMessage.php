<?php

namespace App\Helper\Enums;

enum FirstMessage: string
{
    case init = 'Aqui você pode ficar por dentro das novidades da FURIA CS no youtube, no X, e ver stats sobre as partidas!';
    case instruction1 = 'Sou o furia_bot, você pode conversar comigo sobre a FURIA CS, FURIA TEAM e FURIA GG!';
    case instruction2 = 'Você também pode conversar com os otros FURIOSOS no chat da team_furia_cs ou no chat privado!';
    case instruction3 = 'Posso salvar algumas preferencias suas sobre a FURIA, jogos e partidas quando você conversar comigo.';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
