<?php
require_once "config.php";
require_once "db.php";
require_once "./lib/autoload.php";
require_once 'YandexMusicParser.php';

header('Content-Type: application/json; charset=utf-8');

$url = "https://music.yandex.ru/artist/36800/tracks";
$start_time = microtime(true);

$authorPage = new YandexMusicParser($url);

$authorPage->get_html_code();
$res = $authorPage->parse_by_html_code();

$end_time = number_format((microtime(true) - $start_time) * 1000, 4, '.', '');
echo  json_encode(['response' => $res, 'time' => $end_time, 'errors' => logger::$errors]);
