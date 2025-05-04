<?php

namespace App\Helper\Enums;

enum BotOptionsMessage: string
{
    case ytFuriaCs = 'https://www.youtube.com/@FURIAggCS/playlists';

    case twitter = 'https://x.com/FURIA?ref_src=twsrc%5Egoogle%7Ctwcamp%5Eserp%7Ctwgr%5Eauthor';

    case furiaGg = 'https://www.furia.gg/produtos';

    case matches = 'https://www.hltv.org/results?team=8297';

    case events = 'https://www.hltv.org/team/8297/furia#tab-eventsBox';

    case teamNews = 'https://www.hltv.org/team/8297/furia#tab-newsBox';

    case stats = 'https://www.hltv.org/stats/teams/8297/furia';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
