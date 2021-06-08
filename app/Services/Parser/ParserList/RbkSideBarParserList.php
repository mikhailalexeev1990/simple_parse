<?php

namespace App\Services\Parser\ParserList;

use PHPHtmlParser\Dom\Node\Collection;

class RbkSideBarParserList extends AbstractParserList
{
    const ELEMENT_NEWS_LIST = '.news-feed__wrapper .js-news-feed-list a';
    const ELEMENT_NEWS_TITLE = '.news-feed__item__title';
    const RBK_MAIN = 'www.rbc.ru';
    const RBK_URL_MODIF = 'www.rbc.ru/v10/ajax/get-news-feed/project/spb_sz/lastDate/';

    public function getListData(string $page_link = '', string $el_list = ''): ?Collection
    {
        return parent::getListData(self::RBK_MAIN, self::ELEMENT_NEWS_LIST);
    }

    public function checkListAmount(): bool
    {
        if (count($this->list) < $this->list_amount) {
            sleep($this->parser_timeout);
            $next_news = $this->getDom($this->getNextListHtml())->find('a');
            $this->parseList($next_news);

            return $this->checkListAmount();
        }

        return true;
    }

    public function getNextListHtml()
    {
        $last_news = $this->list[array_key_last($this->list)];
        $url = self::RBK_URL_MODIF . $last_news['modif'] . '/limit/22?_=' . time();

        $response = \Http::get($url);
        $next_news = $response->body();
        \Storage::put('next1.json', $next_news);
        $next_news = json_decode($next_news, JSON_OBJECT_AS_ARRAY)['items'];
        $html = implode('', array_column($next_news, 'html'));

        return "$html";
    }

    public function parseList(Collection $news_list)
    {
        foreach ($news_list as $item) {
            if (count($this->list) >= $this->list_amount) {
                return true;
            }

            if (!count($item)) {
                continue;
            }

            $title = $this->getTitle($item, self::ELEMENT_NEWS_TITLE);
            $link = $this->getLink($item);
            $modif = $this->getModif($item);

            if (!empty($title)) {
                $this->list[$title] = [
                    'title' => $title,
                    'link' => $link,
                    'modif' => $modif,
                    'target_blank' => 0,
                ];
            }
        }
    }
}
