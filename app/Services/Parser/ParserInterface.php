<?php

namespace App\Services\Parser;

use App\Services\Parser\ParserList\ParserListInterface;
use App\Services\Parser\ParserPage\ParserPageInterface;

interface ParserInterface
{
    /**
     * @return mixed
     */
    public function parseData();

    /**
     * @return mixed
     */
    public function saveData();

    /**
     * @param ParserPageInterface $parser_page
     *
     * @return mixed
     */
    public function setPageFabric(ParserPageInterface $parser_page);

    /**
     * @return ParserPageInterface
     */
    public function getPageFabric(): ParserPageInterface;

    /**
     * @param ParserListInterface $parser_list
     *
     * @return mixed
     */
    public function setListFabric(ParserListInterface $parser_list);

    /**
     * @return ParserListInterface
     */
    public function getListFabric(): ParserListInterface;
}
