<?php

namespace App\Console\Commands;

use App\Services\Parser\ParserInterface;
use Illuminate\Console\Command;

class ParseRbkNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse-rbk:news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse news from rbk.ru';

    /**
     * @var ParserInterface
     */
    private ParserInterface $parser;

    /**
     * Create a new command instance.
     *
     * @param ParserInterface $parser
     */
    public function __construct(ParserInterface $parser)
    {
        parent::__construct();
        $this->parser = $parser;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->parser->parse();

        return true;
    }
}
