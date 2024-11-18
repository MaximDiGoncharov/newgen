<?php
class logger
{
    public static $errors = [];

    static function add(string $e)
    {
        self::$errors[] = $e;
    }
}
