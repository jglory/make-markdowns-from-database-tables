<?php

namespace App\Console\Commands;

use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Table;
use Illuminate\Console\Command;
use DB;
use Schema;
use Storage;
use App\Console\Commands\MakeMarkdownFromDatabaseTable\Generators\Lotte as LotteGenerator;


class MakeMarkdownFromDatabaseTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-markdown-from-database-table {database} {schema} {table}';

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
        $generator = new LotteGenerator($this->argument("database"), $this->argument("schema"), $this->argument("table"));
        echo ($generator->title() . PHP_EOL);
        Storage::put($generator->title() . '.md', $generator->contents());
    }
}
