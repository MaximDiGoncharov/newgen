<?php

class YandexMusicParser
{

    public $url;
    protected $dom;


    public function __construct($url)
    {
        $this->url = $url;
    }

    public function parse()
    {
        $this->get_html_code();
        if ($this->dom) {
            return $this->parse_by_html_code();
        } else {
            logger::add("Ошибка при парсинге страницы");
        }
    }
    public function get_html_code()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'); // Имитация браузера
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.5',
        ]);


        $html = curl_exec($ch);

        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        @$dom->loadHTML($html);
        $this->dom = new DOMXPath($dom);

        return $this->dom;
    }

    public function parse_by_html_code()
    {
        $album_arr = [];
        $answ = [];
        $trackNode = $this->dom->query('//div[contains(@class, "d-generic-page-head__main")]')->item(0);
        $artist = [
            'artist_code' => $this->dom->query('.//button[contains( @class, "button-play")]', $trackNode)->item(0)->attributes->item(2)->value,
            'name' => $this->dom->query('.//h1', $trackNode)->item(0)->textContent,
            'listeners' => (int) str_replace(' ', '', $this->dom->query('.//div[contains( @class, "page-artist__summary")]/span', $trackNode)->item(0)->textContent),
            'likes' => (int) str_replace(' ', '', $this->dom->query('.//span[contains( @class, "d-button__label")]', $trackNode)->item(0)->textContent)
        ];

        $model = new artist();
        $artist_id = $model->create($artist);


        $trackNodes = $this->dom->query('//div[contains(@class, "d-track typo-track d-track_selectable")]');

        foreach ($trackNodes as $trackNode) {


            $track_id = $trackNode->attributes->item(1)->value;
            $name = $this->dom->query('.//a[contains( @class, "d-track__title deco-link deco-link_stronger")]', $trackNode)->item(0)->textContent;
            $album = $this->dom->query('.//div[@class="d-track__overflowable-wrapper"]/div[@class="d-track__meta"]/a', $trackNode)->item(0)->textContent;

            if (!isset($album_arr[$album])) {
                $model = new album();
                $album_id = $model->create(['album_name' => $album, 'artist_id' => $artist_id, 'album_create_data' => time()]);
                if ($album_id) {
                    $album_arr[$album] = $album_id;
                }
            }

            $duration = $this->dom->query('.//span[contains( @class, "typo-track deco-typo-secondary")]', $trackNode)->item(0)->textContent;
            $duration = str_replace(':', '.', $duration);

            $link = $this->dom->query('.//a[contains( @class, "d-track__title deco-link deco-link_stronger")]', $trackNode)->item(0)->getAttribute('href');
            $link = explode('/', $link)[4];


            try {
                $obj = ['name' => $name, 'album_id' =>  $album_arr[$album], 'duration' =>  $duration, 'artist_id' => $artist_id, 'track_code' => $link];
                $model = new track();
                $track_id  = $model->create($obj);
                if ($track_id) {
                    $answ[] = $track_id;
                }
            } catch (Throwable $e) {
                logger::add("Этот трек не может быть добавлен - " . $name . ' Причина: ' . $e->getMessage());
            }
        }

        return  $answ;
    }
}
