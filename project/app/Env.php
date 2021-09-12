<?php

namespace App;

class Env
{
    public const PRODUCTION  = 'production';
    public const DEVELOPMENT = 'development';
    public const TESTING     = 'testing';

    protected string $_filePath;

    protected static array $_env = [];

    public function __construct(string $filePath)
    {
        $this->_filePath = rtrim($filePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '.env';
    }

    public static function isProduction(): bool
    {
        return env('APP_ENV') === self::PRODUCTION;
    }

    public static function isDevelopment(): bool
    {
        return env('APP_ENV') === self::DEVELOPMENT;
    }

    public static function isTesting(): bool
    {
        return env('APP_ENV') === self::TESTING;
    }

    /**
     * @return array
     */
    public static function getAll(): array
    {
        $envObject = new \ReflectionClass(self::class);
        return array_values($envObject->getConstants());
    }

    public static function isWindows(): bool
    {
        return stripos(\PHP_OS_FAMILY, 'Windows') === 0;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function load(): void
    {
        if (! file_exists($this->_filePath) || ! is_readable($this->_filePath)) {
            throw new \InvalidArgumentException(
                sprintf('Unable to read the environment file at %s.', $this->_filePath)
            );
        }
        $autodetect = ini_get('auto_detect_line_endings');
        ini_set('auto_detect_line_endings', '1');
        $lines = file($this->_filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        ini_set('auto_detect_line_endings', $autodetect);

        foreach ($lines as $line) {
            self::put($line);
        }
    }

    /**
     * @param $line
     */
    public static function put($line): void
    {
        $vars = parse_ini_string($line, false, INI_SCANNER_TYPED) ?: [];
        foreach ($vars as $k => $v) {
            self::set($k, $v);
        }
    }

    /**
     * @param $key
     * @param $value
     */
    public static function set($key, $value): void
    {
        static::$_env[$key] = $value;
        $_ENV[$key] = $value;
    }

    /**
     * @param string|null $name
     * @param null $default
     * @return mixed
     */
    public static function get(?string $name = null, $default = null): mixed
    {
        if (! $name) {
            return self::$_env;
        }
        switch (true) {
            case array_key_exists($name, static::$_env):
                return static::$_env[$name];
            case array_key_exists($name, $_ENV):
                return $_ENV[$name];
            case array_key_exists($name, $_SERVER):
                return $_SERVER[$name];
            default:
                $value = getenv($name);
                return $value === false ? $default : $value;
        }
    }
}
