<?php

namespace App\Services\Parser;

use PHPHtmlParser\Dom;

trait ParserTrait
{
    public function getDom(string $html)
    {
        $dom = new Dom();
        $dom->loadStr($html);

        return $dom;
    }

    public function printErrors(string $name)
    {
        if (!empty($this->errors)) {
            dump("Errors in $name");
            foreach ($this->errors as $i => $error) {
                $index = $i + 1;
                dump("$index) error - $error");
            }
            dump(PHP_EOL);
        }
    }

    public function deleteAllTags(string $html): string
    {
        return preg_replace("/\s+/", ' ', preg_replace('/<[^>]+>/', '', $html));
    }

    public function makeClass($class_name)
    {
        if (!app()->bound($class_name)) {
            app()->singleton($class_name);
        }

        return app($class_name);
    }
}
