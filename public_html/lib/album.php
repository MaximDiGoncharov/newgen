<?php

class album
{
    static function obj(): db_object
    {
        return new db_object(
            __CLASS__,
            ['album_id','album_name', 'artist_id', 'album_create_date'],
            ['album_name', 'artist_id', 'album_create_date'],
            ['artist_id', 'album_create_date']
        );
    }


    function create(array $param)
    {
        return self::obj()->_add($param);
    }
}
