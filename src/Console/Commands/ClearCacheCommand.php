<?php


namespace MennoVanHout\JMS\Console\Commands;

use File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jms:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear JMS Cache';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $directory = config('jms.cache');

        if (File::exists($directory)) {
            File::deleteDirectory($directory);
        }

        $this->info('JMS cache cleared!');
    }
}
