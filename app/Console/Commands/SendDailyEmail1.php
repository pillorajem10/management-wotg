<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\BlogController;

class SendDailyEmail1 extends Command
{
    protected $signature = 'email:send-daily1';
    protected $description = 'Send daily email with blogs released today';

    public function handle()
    {
        // Create an instance of the BlogController
        $blogController = new BlogController();

        $blogController->sendDailyEmailUsers();

        $this->info('Daily emails sent successfully!');
    }
}
