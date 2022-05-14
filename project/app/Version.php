<?php

namespace App;

use Phalcon\Support\Version as BaseVersion;

class Version extends BaseVersion
{
    /** @var string Released last Date */
    private static string $_dateTime = '2022-05-15 01:00:00';

    /**
     * 1: VERSION_MAJOR
     * 2: VERSION_MEDIUM (two digits)
     * 3: VERSION_MINOR  (two digits)
     * 4: VERSION_SPECIAL → 1 = Alpha, 2 = Beta, 3 = RC, 4 = Stable
     * 5: VERSION_SPECIAL_NUMBER → RC1, Beta2 etc.
     *
     * @return array
     */
    protected static function _getVersion(): array
    {
        return [
            0,  // Application main version
            0,  // Count of successful releases
            1,  // Count of (features + improvements + solved bugs)
            0,  // pre-release → 1 = Alpha, 2 = Beta, 3 = RC, 4 = Stable
            0,  // RC1, Beta2 etc.
        ];
    }

    public static function releaseDate($dateFormat = 'd.m.Y H:i'): string
    {
        return date($dateFormat, strtotime(self::$_dateTime));
    }

    public static function releaseNice(): string
    {
        $version = new self();
        return sprintf('App-Version %s from %s', $version->get(), $version::releaseDate());
    }

    public static function releaseHistory(): array
    {
        return [
            '0.0.1' => '2022-05-15',
        ];
    }
}
