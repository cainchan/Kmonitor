<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;
use App\MonitorData;
use App\WalletSetting;
use App\Mail\OfflineWarning;

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
	// 删除更新时间超过1天
	MonitorData::where('updated_at','<',date('Y-m-d H:i:s',strtotime("-1 day")))->delete();
	// 获取更新时间超过5分钟的数据进行提醒
	$monitor_data = MonitorData::where('updated_at','<',date('Y-m-d H:i:s',strtotime("-5 minute")))->get();
	foreach($monitor_data as $miner){
		// 通过wallet查询数据
		$ret = WalletSetting::where('wallet',$miner->wallet)->first();
		if (!empty($ret)){
			if ($miner->wallet != '0x0506823c25da021db639aa61a3c6d8636bbe3f42'){
				if (empty($ret->email)){continue;}
				Mail::to($ret->email)->send(new OfflineWarning($miner));
				echo sprintf("miner %s offline at %s , send mail to %s%s",$miner->miner,$miner->updated_at,$ret->email,PHP_EOL);
			}else{
				$wechat = app('wechat');
				$notice = $wechat->notice;
				$userId = 'oLvgW1JSUlsQiF6pcNWZr02CLHPE';
				$templateId = 'vOFqtYSmBq-CqqZiA6id43FOW1BPrfAKNOOZYlYIap0';
				$url = 'http://monitor.kaychen.cn/wallet/0x93019bdf0c43a968630b58bda2a669ead3aff7ae';
				$data = array(
					 "first"  => $miner->miner."离线提醒",
					 "miner"   => $miner->miner,
					 "updated_at"  => $miner->updated_at,
					 "remark" => "请及时处理",
				);
				$result = $notice->uses($templateId)
						->withUrl($url)
						->andData($data)
						->andReceiver($userId)
						->send();
				echo json_encode($result);
			}

		}	
		
	}
    }
}
