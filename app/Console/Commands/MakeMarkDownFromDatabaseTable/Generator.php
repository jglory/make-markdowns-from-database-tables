<?php

namespace App\Console\Commands\MakeMarkDownFromDatabaseTable;

interface Generator
{
    public function title(): string;
    public function table(): string;
    public function indexes(): string;
    public function constraints(): string;
    public function contents(): string;
}
