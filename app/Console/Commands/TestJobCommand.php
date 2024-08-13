<?php

namespace App\Console\Commands;

use App\Jobs\TestJob;
use Illuminate\Console\Command;

class TestJobCommand extends Command
{
    protected $signature = "job:test";
    protected $description = "Dispatch TestJob for WebSocket testing";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        TestJob::dispatch();
        $this->info("TestJob dispatched!");
    }
}
