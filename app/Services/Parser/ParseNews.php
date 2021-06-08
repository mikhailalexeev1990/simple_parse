<?php

namespace App\Services\Parser;

use App\Models\News;
use App\Services\Parser\ParserList\RbkSideBarParserList;
use App\Services\Parser\ParserPage\RbkPage;
use App\Services\Parser\ParserPage\RbkSpbPlusPage;
use App\Services\Parser\ParserPage\RbkSportPage;

class ParseNews extends AbstractParser
{
    const PAGES_CLASSES = [
        self::RBK_MAIN => RbkPage::class,
        self::RBK_SPB_PLUS => RbkSpbPlusPage::class,
        self::RBK_SPORT => RbkSportPage::class,
    ];

    public array $news = [];

    public string $parser_page_class;

    public function parseData()
    {
        $this->setListFabric($this->makeClass(RbkSideBarParserList::class));

        dump("parse news");
        $list = $this->parser_list->getListData();
        $this->parser_list->parseList($list);
        $this->parser_list->checkListAmount();
        $this->news = $this->parser_list->getList();
        dump("end parse news");
        dump(PHP_EOL);

        dump("parse page");
        $this->parsePage();
        dump("end parse page");
        dump(PHP_EOL);

        dump('saveData');
        $this->saveData();
        dump("end save data");
        dump(PHP_EOL);
    }

    public function parsePage()
    {
        if (!empty($this->news)) {
            foreach ($this->news as $i => $news_item) {
                $link = $this->getHostLink($news_item['link']);

                switch ($link) {
                    case self::RBK_MAIN:
                        $this->parser_page_class = self::PAGES_CLASSES[self::RBK_MAIN];
                        break;
                    case self::RBK_SPB_PLUS:
                        $this->parser_page_class = self::PAGES_CLASSES[self::RBK_SPB_PLUS];
                        break;
                    case self::RBK_SPORT:
                        $this->parser_page_class = self::PAGES_CLASSES[self::RBK_SPORT];
                        break;
                    default:
                        $this->news[$news_item['title']]['target_blank'] = 1;
                        continue 2;
                }

                $this->setPageFabric($this->makeClass($this->parser_page_class));
                $parser_page = $this->getPageFabric();
                $parser_page->setLink($news_item['link']);
                $this->news[$i] = array_merge($news_item, $parser_page->parsePage());
            }
        }
    }

    public function saveData()
    {
        if (count($this->news)) {
            foreach ($this->news as $news_item) {
                dump("save news - {$news_item['title']}");
                if (!empty($news_item['image'])) {
                    $news_item['image']['id'] = $this->saveImg(
                        $news_item['image']['image_src'],
                        $news_item['image']['image_alt']
                    );
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
