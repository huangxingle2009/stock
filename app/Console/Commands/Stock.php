<?php

namespace App\Console\Commands;

use App\Service\StockService;
use Illuminate\Console\Command;

class Stock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get stock data from aigupiao.com';

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
        StockService::worm();

    }
}
