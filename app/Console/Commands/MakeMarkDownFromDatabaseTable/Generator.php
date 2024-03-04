<?php

namespace App\Console\Commands\MakeMarkDownFromDatabaseTable;

interface Generator
{
    public function title(): string;
    public function table(): string;
    public function indexes(): string;

    public function body(): string;

    public function save(): bool;
}
