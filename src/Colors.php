<?php

namespace Codedungeon\PHPUnitPrettyResultPrinter;

class Colors
{
    const NORMAL = "\e[0m";
    const RESET = "\e[0m";
    const RED = "\e[31m";
    const WHITE = "\e[0m";
    const GREEN = "\e[32m";
    const CYAN = "\e[36m";
    const BLUE = "\e[34m";
    const PURPLE = "\e[35m";
    const MAGENTA = "\e[35m";
    const YELLOW = "\e[33m";
    const GRAY = "\e[90m";

    const LWHITE = "\e[1;37m";
    const LGRAY = "\e[1;30m";
    const LPURPLE = "\e[1;35m";
    const LMAGENTA = "\e[1;35m";
    const LBLUE = "\e[1;34m";
    const LGREEN = "\e[1;32m";
    const LCYAN = "\e[1;36m";
    const LRED = "\e[1;31m";
    const LYELLOW = "\e[93m";

    const DGRAY = "\e[1;30m";

    /**
     * Colors constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public static function reset()
    {
        return self::RESET;
    }

    /**
     * @param int $light
     * @return string
     */
    public static function red($light = 0)
    {
        return ($light === 1) ? self::lred() : self::RESET . self::RED;
    }

    /**
     * @return string
     */
    public static function lred()
    {
        return self::RESET . self::LRED;
    }

    /**
     * @param int $light
     * @return string
     */
    public static function white($light = 0)
    {
        return ($light === 1) ? self::lwhite() : self::RESET . self::WHITE;
    }

    /**
     * @param int $light
     * @return string
     */
    public static function lwhite($light = 0)
    {
        return self::RESET . self::LWHITE;
    }

    /**
     * @param int $light
     * @return string
     */
    public static function gray($light = 0)
    {
        return ($light === 1) ? self::lgray() : self::RESET . self::GRAY;
    }

    /**
     * @param int $light
     * @return string
     */
    public static function green($light = 0)
    {
        return ($light === 1) ? self::lgreen() : self::RESET . self::GREEN;
    }

    /**
     * @return string
     */
    public static function lgreen()
    {
        return self::RESET . self::LGREEN;
    }

    /**
     * @param int $light
     * @return string
     */
    public static function cyan($light = 0)
    {
        return ($light === 1) ? self::lcyan() : self::RESET . self::CYAN;
    }

    /**
     * @return string
     */
    public static function lcyan()
    {
        return self::RESET . self::LCYAN;
    }

    /**
     * @param int $light
     * @return string
     */
    public static function yellow($light = 0)
    {
        return ($light === 1) ? self::lyellow() : self::RESET . self::YELLOW;
    }

    /**
     * @return string
     */
    public static function lyellow()
    {
        return self::RESET . self::LYELLOW;
    }

    /**
     * @return string
     */
    public static function lgray()
    {
        return self::RESET . self:: LGRAY;
    }

    /**
     * @param int $light
     * @return string
     */
    public static function purple($light = 0)
    {
        return ($light === 1) ? self::lpurple() : self::RESET . self::PURPLE;
    }

    /**
     * @return string
     */
    public static function lpurple()
    {
        return self::RESET . self::LPURPLE;
    }

    /**
     * @param int $light
     * @return string
     */
    public static function magenta($light = 0)
    {
        return ($light === 1) ? self::lmagenta() : self::RESET . self::MAGENTA;
    }

    /**
     * @return string
     */
    public static function lmagenta()
    {
        return self::RESET . self::LMAGENTA;
    }
}
