<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http; // Import Laravel HTTP Client
use App\Models\User;

class FacebookFollow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'facebook_follow:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is a facebook_follow command for testing purposes';

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
     * @return int
     */
    public function handle()
    {
       
        Log::info('API Response:');

        
    }
}
