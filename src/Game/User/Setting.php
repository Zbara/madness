<?php

namespace App\Game\User;

use App\Entity\Settings;
use JetBrains\PhpStorm\ArrayShape;

class Setting
{

    #[ArrayShape([
        'sound' => "string",
        'sound_volume' => "int",
        'music' => "string",
        'music_volume' => "int",
        'game_url' => "string"
    ])]
    public function getSettings(Settings $settings): array
    {
        return [
            'sound' => ($settings->getSound()) ? 'yes' : 'no',
            'sound_volume' => (int)$settings->getSoundVolume(),
            'music' => ($settings->getMusic()) ? 'yes' : 'no',
            'music_volume' => (int)$settings->getMusicVolume(),
            'game_url' => '{game_url}'
        ];
    }
}
