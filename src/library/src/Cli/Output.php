<?php

namespace Library\Cli;

class Output
{
    public const COLOR_NONE = "\e[0m";
    public const COLOR_RED = "\e[0;31m";
    public const COLOR_LIGHT_RED = "\e[0;91m";
    public const COLOR_YELLOW = "\e[1;33m";
    public const COLOR_LIGHT_YELLOW = "\e[1;93m";
    public const COLOR_GREEN = "\e[0;32m";
    public const COLOR_LIGHT_GREEN = "\e[0;92m";
    public const COLOR_BLUE = "\e[0;34m";
    public const COLOR_LIGHT_CYAN = "\e[0;96m";

    protected static string $_stderr = '';
    protected static string $_stdout = '';

    public static function error(string $msg): void
    {
        self::console(STDERR, $msg, self::COLOR_LIGHT_RED);
        self::$_stderr .= $msg . PHP_EOL;
    }

    public static function debug(string $msg): void
    {
        self::console(STDOUT, $msg, self::COLOR_YELLOW);
        self::$_stdout .= $msg . PHP_EOL;
    }

    public static function info(string $msg): void
    {
        self::console(STDOUT, $msg, self::COLOR_LIGHT_CYAN);
        self::$_stdout .= $msg . PHP_EOL;
    }

    public static function text(string $msg, string $color = self::COLOR_NONE): void
    {
        self::console(STDOUT, $msg, $color);
        self::$_stdout .= $msg . PHP_EOL;
    }

    /**
     * @param resource $stream
     * @param string $msg
     * @param string $color
     * @return void
     */
    protected static function console($stream, string $msg, string $color = self::COLOR_NONE): void
    {
        fwrite($stream, $color . $msg . self::COLOR_NONE . PHP_EOL);
    }

    /**
     * @return string|null
     */
    public static function getText(): ?string
    {
        return self::$_stdout ?: null;
    }

    /**
     * @return string|null
     */
    public static function getError(): ?string
    {
        return self::$_stderr ?: null;
    }

    public static function clear(): void
    {
        self::$_stderr = '';
        self::$_stdout = '';
    }

    public static function cursor(): Cursor
    {
        return new Cursor();
    }
}
