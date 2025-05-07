<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RestartQueueCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:restart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restart Laravel queue workers';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Restarting queue workers.');

        // Restart queue workers
        $this->call('queue:restart');

        Log::info('Queue workers restarted successfully.');
    }
}
