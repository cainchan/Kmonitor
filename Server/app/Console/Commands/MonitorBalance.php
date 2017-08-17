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
		// 获取余额
		$b = strstr($a,'<div class="table-responsive">');
		$b = strstr($b,'<table id="workers"',TRUE);
		phpQuery::newDocument($b);
		$c = pq('table td:last')->text();
		$wallet->balance = sprintf("%0.6f",$c);;
		$wallet->updated_at = date('Y-m-d H:i:s');
		// 获取最后一次付款日期+金额
		$b = strstr($a,'<div style="color: #666666;">');
		$b = strstr($b,'<footer class="footer">',TRUE);
		phpQuery::newDocument($b);
		$c = pq('table td:first')->text();
		if (!empty($c)){
			// 获取最后一次付款日期+金额
			$matchP = "/([^\d]*)(?P<time>\d*)([^\d]*)/";
			preg_match($matchP,$c,$d);
			$wallet->last_paid_date = date('Y-m-d',$d['time']);
			// 获取最后一次付款金额
			$c = pq('table td:eq(2)')->text();
			$wallet->last_paid_balance = sprintf("%0.6f",$c);
		}
		// 获取当前ETH价格
		$a = file_get_contents('https://www.okcoin.cn/real/ticker.do?symbol=2');
		$wallet->price = $a;
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
