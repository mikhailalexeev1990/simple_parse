<?php

namespace App\Services\Parser\ParserPage;

use PHPHtmlParser\Dom\Node\Collection;
use App\Services\Parser\ParserTrait;

abstract class AbstractParserPage implements ParserPageInterface
{
    use ParserTrait;

    protected string $link;

    abstract public function parsePage(): array;

    public function setLink(string $link)
    {
        $this->link = $link;

        return $this;
    }

    public function getImg(Collection $img): ?array
    {
        if (count($img)) {
            return [
                'image_src' => $img->getAttribute('src'),
                'image_alt' => $img->getAttribute('alt'),
            ];
        }

        return null;
    }

    public function getInfo(Collection $info): ?string
    {
        if (count($info)) {
            $info_list = [];
            foreach ($info as $text_item) {
                $info_list[] = $this->deleteAllTags($text_item->innerHtml);
            }

            return implode(PHP_EOL, $info_list);
        }

        return null;
    }

    public function __destruct()
    {
        $this->printErrors('ParserPage');
    }
}
