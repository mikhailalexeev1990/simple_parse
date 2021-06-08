<?php

namespace App\Services\Parser;

use App\Models\Images;
use App\Services\Parser\ParserList\ParserListInterface;
use App\Services\Parser\ParserPage\ParserPageInterface;

abstract class AbstractParser implements ParserInterface
{
    use ParserTrait;

    public const RBK_SPB_PLUS = 'spb.plus.rbc.ru';
    public const RBK_MAIN = 'www.rbc.ru';
    public const RBK_SPORT = 'sportrbc.ru';

    /**
     * @var array
     */
    public array $errors = [];

    /**
     * @var ParserPageInterface
     */
    public ParserPageInterface $parser_page;

    /**
     * @var ParserListInterface
     */
    public ParserListInterface $parser_list;

    public function parse()
    {
        $this->parseData();
        $this->saveData();
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

    public function addError(\Throwable $e)
    {
        $this->errors[] = "error - {$e->getMessage()}, file - {$e->getFile()}:{$e->getLine()}";
    }

    public function getHostLink(string $link)
    {
        return parse_url($link, PHP_URL_HOST);
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

    public function setPageFabric(ParserPageInterface $parser_page)
    {
        $this->parser_page = $parser_page;

        return $this;
    }

    public function getPageFabric(): ParserPageInterface
    {
        return $this->parser_page;
    }

    public function setListFabric(ParserListInterface $parser_list)
    {
        $this->parser_list = $parser_list;

        return $this;
    }

    public function getListFabric(): ParserListInterface
    {
        return $this->parser_list;
    }
}
