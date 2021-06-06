<?php

namespace App\Services\Parser;

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
}
