<?php

class artist
{
    static function obj(): db_object
    {
        return new db_object(
            __CLASS__,
            ['name', 'listeners', 'likes', 'artist_code'],
            ['name', 'listeners', 'likes', 'artist_code'],
            ['listeners', 'likes', 'artist_code']
        );
    }


    function create(array $param)
    {
        return self::obj()->_add($param);
    }
}
