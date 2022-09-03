<?php

namespace App;

use Phalcon\Support\Version as BaseVersion;

class Version extends BaseVersion
{
    /** @var string Released last Date */
    private static string $_dateTime = '2022-08-30 17:00';

    /**
     * 1: VERSION_MAJOR
     * 2: VERSION_MEDIUM (two digits)
     * 3: VERSION_MINOR  (two digits)
     * 4: VERSION_SPECIAL → 1 = Alpha, 2 = Beta, 3 = RC, 4 = Stable
     * 5: VERSION_SPECIAL_NUMBER → RC1, Beta2 etc.
     *
     * {@inheritdoc}
     * @return array
     */
    protected function getVersion(): array
    {
        return [
            0,  // Application main version
            0,  // Count of successful releases
            1,  // Count of (features + improvements + solved bugs)
            0,  // pre-release → 1 = Alpha, 2 = Beta, 3 = RC, 4 = Stable
            0,  // RC1, Beta2 etc.
        ];
    }

    public function getId(): string
    {
        $version = $this->getVersion();
        return $version[self::VERSION_MAJOR]
             . $version[self::VERSION_MEDIUM]  // no digit limit
             . $version[self::VERSION_MINOR]   // no digit limit
             . $version[self::VERSION_SPECIAL]
             . $version[self::VERSION_SPECIAL_NUMBER];
    }

    public static function releaseNice($dateFormat = 'd.m.Y H:i'): string
    {
        $releaseVersion = (new self())->get();
        $releaseDate = date($dateFormat, strtotime(self::$_dateTime));
        return sprintf('App-Version %s from %s', $releaseVersion, $releaseDate);
    }

    public static function releaseHistory(): array
    {
        return [
            '0.0.1' => '2022-08-30 17:00',
        ];
    }
}
