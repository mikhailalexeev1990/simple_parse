<?php

namespace App\Services\Parser;

use App\Models\News;

class ParseNews extends AbstractParser
{
    public const NEWS_AMOUNT = 15;
    public const PARSER_TIMEOUT = 3;
    public const ELEMENT_NEWS_LIST = '.news-feed__wrapper .js-news-feed-list a';
    public const ELEMENT_NEWS_TITLE = '.news-feed__item__title';
    public const ELEMENT_NEWS_PAGE = '.article__main-image__wrap img';
    public const ELEMENT_NEWS_TEXT_PAGE = '.js-rbcslider-slide.rbcslider__slide .article p';

    public array $news = [];

    public function parseData()
    {
        $response = \Http::get($this->rbk_url);
        $html = $response->body();

        $main_page = $this->getDom($html);
        $news_list = $main_page->find(self::ELEMENT_NEWS_LIST);

        if (!count($news_list)) {
            $this->errors[] = 'news list is empty!';

            return;
        }

        dump("parse news");
        $this->parseNewsList($news_list);
        $this->checkNewsAmount();
        dump("end parse news");
        dump(PHP_EOL);

        dump('saveData');
        $this->saveData();
        dump("end parse news");
        dump(PHP_EOL);
    }

    public function parseNewsList($news_list)
    {
        foreach ($news_list as $elem) {
            if (count($this->news) >= self::NEWS_AMOUNT) {
                return true;
            }

            if (!count($elem)) {
                continue;
            }

            $title = $elem->find(self::ELEMENT_NEWS_TITLE);

            if (count($title)) {
                $title = $title->innerHtml;
                $link = preg_replace('/\?.*/', '', $elem->getAttribute('href'));
                $modif = $elem->getAttribute('data-modif');

                $this->news[$title] = [
                    'title' => $title,
                    'link' => $link,
                    'modif' => $modif,
                    'target_blank' => 0,
                ];

                if (strpos($link, $this->rbk_url) !== false) {
                    $response = \Http::get($link);
                    $html = $response->body();

                    $page = $this->getDom($html);
                    $img = $page->find(self::ELEMENT_NEWS_PAGE);
                    $p_list = $page->find(self::ELEMENT_NEWS_TEXT_PAGE);

                    if (count($img)) {
                        $this->news[$title]['image'] = [
                            'image_src' => $img->getAttribute('src'),
                            'image_alt' => $img->getAttribute('alt'),
                        ];
                    }

                    if (count($p_list)) {
                        $info_list = [];
                        foreach ($p_list as $p) {
                            $info_list[] = $this->deleteAllTags($p->innerHtml);
                        }
                        $this->news[$title]['info'] = implode(PHP_EOL, $info_list);
                    }
                } else {
                    $this->news[$title]['target_blank'] = 1;
                }
            }
        }
    }

    public function checkNewsAmount()
    {
        if (count($this->news) < self::NEWS_AMOUNT) {
            sleep(self::PARSER_TIMEOUT);
            $next_news = $this->getDom($this->getNextNewsHtml())->find('a');
            $this->parseNewsList($next_news);

            return $this->checkNewsAmount();
        }

        return true;
    }

    public function getNextNewsHtml()
    {
        $last_news = $this->news[array_key_last($this->news)];
        $url = $this->rbk_url_modif . $last_news['modif'] . '/limit/22?_=' . time();

        $response = \Http::get($url);
        $next_news = $response->body();
        \Storage::put('next1.json', $next_news);
        $next_news = json_decode($next_news, JSON_OBJECT_AS_ARRAY)['items'];
        $html = implode('', array_column($next_news, 'html'));

        return "$html";
    }

    public function saveData()
    {
        if (count($this->news)) {
            foreach ($this->news as $news_item) {
                dump("save news - {$news_item['title']}");

                if (!empty($news_item['image'])) {
                    $news_item['image']['id'] = $this->saveImg($news_item['image']['image_src'], $news_item['image']['image_alt']);
                }

                $this->saveNews($news_item);
            }
        }

        return true;
    }

    public function saveNews(array $news_item)
    {
        if (!$news = News::where('title', $news_item['title'])->first()) {
            $news = new News();
        }

        try {
            $news->title = $news_item['title'];
            $news->link = $news_item['link'];
            $news->target_blank = $news_item['target_blank'];
            $news->info = $news_item['info'] ?? null;
            $news->image_id = optional($news_item)['image']['id'] ?? null;
            $news->save();

            return $news->id;
        } catch (\Throwable $e) {
            $this->addError($e);

            return null;
        }
    }
}
