<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\BlogController;

class SendDailyEmail2 extends Command
{
    protected $signature = 'email:send-daily2';
    protected $description = 'Send daily email with blogs released today';

    public function handle()
    {
        // Create an instance of the BlogController
        $blogController = new BlogController();

        $blogController->sendDailyEmailSubs();

        $this->info('Daily emails sent successfully!');
    }
}
