<?php

class track
{
    static function obj(): db_object
    {
        return new db_object(
            __CLASS__,
            ['name', 'album_id', 'duration', 'artist_id', 'track_code'],
            ['name', 'album_id', 'duration', 'artist_id', 'track_code'],
            ['artist_id', 'track_code', 'album_id']
        );
    }


    function create(array $param)
    {
        return self::obj()->_add($param);
    }
}
