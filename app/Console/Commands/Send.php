<?php

namespace App\Console\Commands;

use App\Service\NotifyService;
use Illuminate\Console\Command;

class Send extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qq:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send message to qq group';

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

        do {
            // 休眠1
            sleep(1);
            // 处理具体事情
            NotifyService::sendToGroup();
        } while(true);

    }
}
