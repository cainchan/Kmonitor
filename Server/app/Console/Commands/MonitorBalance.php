<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\MonitorData;
use App\WalletSetting;
use phpQuery;

class MonitorBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:balance';

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
	try{
	$ret = WalletSetting::all();
	foreach($ret as $wallet){
		$url = sprintf("https://www.f2pool.com/eth/%s",$wallet->wallet);
		$a = file_get_contents($url);
		$b = strstr($a,'<div class="table-responsive">');
		$b = strstr($b,'<table id="workers"',TRUE);
		phpQuery::newDocument($b);
		$c = pq('table td:last')->text();
		$balance = trim($c," ETH");
		$wallet->balance = sprintf("%0.6f",$balance);;
		$wallet->updated_at = date('Y-m-d H:i:s');
		$wallet->save();
		echo sprintf("%s %s balance=%s%s",
			date('Y-m-d H:i:s'),
			$wallet->wallet,
			$wallet->balance,
			PHP_EOL
		);
	}
	}catch(Exception $e){
		echo date('Y-m-d H:i:s').'monitor balance failed';
		//throw $e;
	}
    }
}
