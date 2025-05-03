<?php

namespace App\Helper\Enums;

enum BotOptionsMessage : string
{
    case ytFuriaCs = 'https://www.youtube.com/@FURIAggCS/playlists';

    case twitter = 'https://x.com/FURIA?ref_src=twsrc%5Egoogle%7Ctwcamp%5Eserp%7Ctwgr%5Eauthor';

    case furiaGg = 'https://www.furia.gg/produtos';

    case macthes = '';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
