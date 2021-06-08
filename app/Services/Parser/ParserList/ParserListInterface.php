<?php

namespace App\Services\Parser\ParserList;

use PHPHtmlParser\Dom\Node\Collection;

interface ParserListInterface
{
    public function parseList(Collection $news_list);

    public function getListData(string $page_link, string $el_list): ?Collection;

    public function getList(): array;

    public function checkListAmount(): bool;
}
