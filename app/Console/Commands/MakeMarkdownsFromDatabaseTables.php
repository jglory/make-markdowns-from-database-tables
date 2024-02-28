<?php

namespace App\Console\Commands;

use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Table;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;



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

        $tables = DB::connection($this->argument("database"))->getDoctrineSchemaManager()->listTables();

        uasort($tables, function ($a, $b): ?int {
            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? -1 : 1;
        });
        foreach ($tables as $table) {
            echo "테이블명: " . $table->getName() . "\n";
            continue;
            // 특정 테이블의 컬럼 정보 가져오기
            $columns = $table->getColumns();

            echo "컬럼 목록:\n";
            foreach ($columns as $columnName => $column) {
                $columnDescription = $column->getComment() ?: '설명 없음'; // 컬럼 설명이 없으면 '설명 없음'으로 표시
                echo "- $columnName: $columnDescription\n";
            }

            // 특정 테이블의 인덱스 정보 가져오기
            //$indexes = Schema::getConnection()->getDoctrineSchemaManager()->listTableIndexes($table);
            $indexes = $table->getIndexes();

            echo "인덱스 목록:\n";
            foreach ($indexes as $index) {
                echo "- 이름: {$index->getName()}, 유니크: " . ($index->isUnique() ? '예' : '아니오') . "\n";
                echo "  컬럼: " . implode(', ', $index->getColumns()) . "\n";
            }

            // 주석 추가: 현재 테이블에 대한 정보를 출력했음을 나타냄
            echo "//".$table->getName()." 테이블에 대한 정보 출력 완료\n\n";
        }
    }
}
