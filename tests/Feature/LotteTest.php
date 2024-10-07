<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Console\Commands\MakeMarkDownFromDatabaseTable\Generators\Lotte as Generator;

class LotteTest extends TestCase
{
    private $generator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generator = new Generator('oracle', 'EC_MGR', 'PR_GOODS_BASE');
    }

    /**
     *
     */
    public function test_that_generator_output_title(): void
    {
        $this->assertTrue(true);
        echo($this->generator->title());
        $this->assertNotNull($this->generator->title());
    }
}
