<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class CreateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $db_name = env('DB_DATABASE', 'parser_local');
        $db_connection = env('DB_CONNECTION', 'mysql');

        $sql = "
            CREATE DATABASE IF NOT EXISTS $db_name
            DEFAULT CHARACTER SET = 'utf8mb4'
            DEFAULT COLLATE 'utf8mb4_unicode_ci'
        ";

        try {
            config()->set("database.connections.$db_connection.database", null);
            \DB::connection($db_connection)->statement($sql);
            dump('success');
        } catch (\Exception $e) {
            dump($e->getMessage());
        }
    }
}
