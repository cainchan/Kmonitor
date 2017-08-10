<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\MonitorData;
use App\WalletSetting;

class MonitorMiner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:miner';

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
	$monitor_data = MonitorData::where('updated_at','<',date('Y-m-d H:i:s',strtotime("+5 minute")))->get();
	foreach($monitor_data as $miner){
		echo $miner->wallet.PHP_EOL;
	}
    }
}
