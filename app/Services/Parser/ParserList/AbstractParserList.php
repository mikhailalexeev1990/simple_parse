<?php

namespace App\Services\Parser\ParserList;

use PHPHtmlParser\Dom\Node\Collection;
use App\Services\Parser\ParserTrait;
use PHPHtmlParser\Dom\Node\HtmlNode;

abstract class AbstractParserList implements ParserListInterface
{
    use ParserTrait;

    protected int $list_amount = 15;

    protected int $parser_timeout = 3;

    protected array $list = [];

    protected array $errors = [];

    abstract public function parseList(Collection $news_list);

    abstract public function checkListAmount(): bool;

    public function getListData(string $page_link, string $el_list): ?Collection
    {
        $response = \Http::get($page_link);
        $html = $response->body();

        $main_page = $this->getDom($html);
        $list = $main_page->find($el_list);

        if (!count($list)) {
            $this->errors[] = 'Parse list is empty!';

            return null;
        }

        return $list;
    }

    public function setListAmount(int $amount)
    {
        $this->list_amount = $amount;

        return $this;
    }

    public function getList(): array
    {
        return $this->list;
    }

    public function getTitle(HtmlNode $item, string $el): string
    {
        $title = $item->find($el);

        return count($title)
            ? $title->innerHtml
            : "";
    }

    public function getLink(HtmlNode $item): string
    {
        return preg_replace('/\?.*/', '', $item->getAttribute('href'));
    }

    public function getModif(HtmlNode $item): string
    {
        return $item->getAttribute('data-modif') ?? "";
    }

    public function __destruct()
    {
        $this->printErrors('ParserList');
    }
}
