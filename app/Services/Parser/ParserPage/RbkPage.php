<?php

namespace App\Services\Parser\ParserPage;

class RbkPage extends AbstractParserPage
{
    const ELEMENT_IMG = '.article__main-image__wrap img';
    const ELEMENT_TEXT = '.rbcslider__slide .article p';

    public function parsePage(): array
    {
        $response = \Http::get($this->link);
        $html = $response->body();
        $page = $this->getDom($html);

        return [
            'image' => $this->getImg($page->find(self::ELEMENT_IMG)),
            'info' => $this->getInfo($page->find(self::ELEMENT_TEXT)),
        ];
    }
}
