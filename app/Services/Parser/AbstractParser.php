<?php

namespace App\Services\Parser;

use App\Models\Images;
use PHPHtmlParser\Dom;

abstract class AbstractParser implements ParserInterface
{
    public array $errors = [];

    /**
     * @var string
     */
    public string $rbk_url = 'https://www.rbc.ru/';

    /**
     * @var string
     */
    public string $rbk_url_modif = 'https://www.rbc.ru/v10/ajax/get-news-feed/project/spb_sz/lastDate/';

    /**
     * @var Dom
     */
    private Dom $dom;

    public function __construct(Dom $dom)
    {
        $this->dom = $dom;
    }

    public function parse()
    {
        $this->parseData();
        $this->saveData();
    }

    public function getDom(string $html)
    {
        $dom = new $this->dom;
        $dom->loadStr($html);

        return $dom;
    }

    public function deleteAllTags(string $html)
    {
        return preg_replace("/\s+/", ' ', preg_replace('/<[^>]+>/', '', $html));
    }

    public function saveImg(string $img_src, string $img_alt): ?int
    {
        $path = explode('/', parse_url($img_src)['path']);
        $img_name = end($path);
        $img_path = "/images/$img_name";

        $response = \Http::get($img_src);
        \Storage::put($img_path, $response->body());

        if (!$img = Images::where('path', $img_path)->first()) {
            $img = new Images();
        }

        try {
            $img->path = $img_path;
            $img->name = $img_alt;
            $img->save();

            return $img->id;
        } catch (\Throwable $e) {
            $this->addError($e);

            return null;
        }
    }

    public function addError(\Throwable $e)
    {
        $this->errors[] = "error - {$e->getMessage()}, file - {$e->getFile()}:{$e->getLine()}";
    }

    public function __destruct()
    {
        if (!empty($this->errors)) {
            foreach ($this->errors as $i => $error) {
                $index = $i + 1;
                dump("$index) error - $error");
            }
        }
    }
}
