<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeMarkdownsFromDatabaseTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-markdowns-from-database-tables {database} {--table=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '데이터베이스 테이블로부터 마크다운 문서를 생성한다.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line($this->argument("database"));
        $this->line($this->option("table"));
    }
}
