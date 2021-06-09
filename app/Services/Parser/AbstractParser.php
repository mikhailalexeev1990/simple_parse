<?php

namespace App\Services\Parser;

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
