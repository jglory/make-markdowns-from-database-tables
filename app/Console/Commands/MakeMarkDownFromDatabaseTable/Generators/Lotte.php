<?php

namespace App\Console\Commands\MakeMarkDownFromDatabaseTable\Generators;

use App\Console\Commands\MakeMarkdownFromDatabaseTable\Generator;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\Index;
use Illuminate\Support\Facades\DB;

class Lotte implements Generator
{
    protected string $database;
    protected string $schema;

    protected AbstractSchemaManager $schemaManager;
    protected Table $table;

    public function __construct(string $database, string $schema, string $table)
    {
        $conn = DB::connection($database);
        $conn->getPdo()->exec('ALTER SESSION SET CURRENT_SCHEMA = ' . $schema);
        $this->schemaManager = $conn->getDoctrineSchemaManager();
        $this->table = $this->schemaManager->introspectTable($table);

        $this->database = $database;
        $this->schema = $schema;
    }

    public function title(): string
    {
        $result = '';

        $result = $this->table->getName();

        return $result;
    }

    public function table(): string
    {
        $result = '';

        $result = "| 컬럼명 | 데이터 타입 |" . PHP_EOL
            . "| ---- | ---- |" . PHP_EOL;

        foreach ($this->table->getColumns() as $column) {
            $result .= "|" . $column->getName() . "|" . "-" . "|" . PHP_EOL;
        }

        return $result;
    }

    public function indexes(): string
    {
        $result = '';
        $indexes = $this->table->getIndexes();

        return $result;
    }

    public function body(): string
    {
        return $this->table() . PHP_EOL . PHP_EOL . $this->indexes() . PHP_EOL;
    }

    public function save(): bool
    {
        return false;
    }
}
