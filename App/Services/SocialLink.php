<?php

namespace Modules\Sociallink\App\Services;

class SocialLink
{
    public static function getIconUrl($iconIdentifier, $color = 'white')
    {
        $parts = explode('-', $iconIdentifier, 2);

        if (count($parts) !== 2) {
            return null;
        }
        $replaceIconColor = str_replace('#', '%23', $color);
        $url = "https://api.iconify.design/{$parts[0]}/{$parts[1]}.svg?color={$replaceIconColor}";

        return $url;
    }
}
