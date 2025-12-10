<?php

namespace App\Console\Commands;

use App\Events\StartComputeBucket;
use Illuminate\Console\Command;
// use App\Events\StartComputeBucket;

class ComputeStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:compute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compute statistics snapshot for the last period';
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        event(new StartComputeBucket(now()));

        return static::SUCCESS;
    }
}
