<?php

namespace App\Services\Parser;

use App\Models\Images;
use PHPHtmlParser\Dom;

trait ParserTrait
{
    public function getDom(string $html)
    {
        $dom = new Dom();
        $dom->loadStr($html);

        return $dom;
    }

    public function printErrors(string $name)
    {
        if (!empty($this->errors)) {
            dump("Errors in $name");
            foreach ($this->errors as $i => $error) {
                $index = $i + 1;
                dump("$index) error - $error");
            }
            dump(PHP_EOL);
        }
    }

    public function addError(\Throwable $e)
    {
        $this->errors[] = "error - {$e->getMessage()}, file - {$e->getFile()}:{$e->getLine()}";
    }

    public function deleteAllTags(string $html): string
    {
        return preg_replace("/\s+/", ' ', preg_replace('/<[^>]+>/', '', $html));
    }

    public function makeClass($class_name)
    {
        if (!app()->bound($class_name)) {
            app()->singleton($class_name);
        }

        return app($class_name);
    }

    public function getHostLink(string $link)
    {
        return parse_url($link, PHP_URL_HOST);
    }

    public function saveImg(?string $img_src, ?string $img_alt): ?int
    {
        if (empty($img_src)) {
            return null;
        }

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
}
