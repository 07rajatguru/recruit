<?php

namespace App\Console\Commands;

use App\EmailsNotifications;
use Illuminate\Console\Command;

class ReportTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        $emails = EmailsNotifications::find(1);
        $emails->status=1;
        $emails->save();
    }
}
