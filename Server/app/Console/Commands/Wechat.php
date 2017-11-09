<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Wechat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:wechat';

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
    public function handle(){
	$wechat = app('wechat');
	$menu = $wechat->menu;
	$buttons = [
	    [
		"type" => "click",
		"name" => "挖矿进度",
		"key"  => "0x93019bdf0c43a968630b58bda2a669ead3aff7ae"
	    ],
	    [
		"name"       => "菜单",
		"sub_button" => [
		    [
			"type" => "view",
			"name" => "Web查看",
			"url"  => "http://monitor.kaychen.cn/wallet/0x93019bdf0c43a968630b58bda2a669ead3aff7ae"
		    ],
		    [
			"type" => "view",
			"name" => "视频",
			"url"  => "http://v.qq.com/"
		    ],
		],
	    ],
	];
	$menu->add($buttons);
    }
    public function sendMessage()
    {
        //
        $wechat = app('wechat');
	$notice = $wechat->notice;
	$userId = 'oLvgW1JSUlsQiF6pcNWZr02CLHPE';
	$templateId = 'vOFqtYSmBq-CqqZiA6id43FOW1BPrfAKNOOZYlYIap0';
	$url = 'http://monitor.kaychen.cn/wallet/0x93019bdf0c43a968630b58bda2a669ead3aff7ae';
	$data = array(
		 "first"  => "Miner离线提醒",
		 "miner"   => "A480",
		 "updated_at"  => "2017-11-09 12:00:00",
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
