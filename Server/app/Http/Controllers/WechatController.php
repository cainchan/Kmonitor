<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MonitorData;
use App\WalletSetting;
use Log;

class WechatController extends Controller
{
    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        $wechat = app('wechat');
        $wechat->server->setMessageHandler(function($message){
		switch ($message->MsgType) {
			case 'text':
			    return '收到文字消息';
			    break;
			case 'event':
				// 获取用户open_id
				// 获取用户钱包地址,如果为空则提示去设置
				// 根据钱包地址+矿池获取余额
				$data = $this->getMonitorData($message->EventKey);
				$text = sprintf("余额:%s\n更新时间:%s\n上次支付:%s\n支付时间:%s\n",$data['setting']['balance'],$data['setting']['updated_at'],$data['setting']['last_paid_balance'],$data['setting']['last_paid_date']);
				foreach($data['results'] as $miner){
					$text .= sprintf("%s:%s\n",$miner['miner'],$miner['updated_at']);
				}
				return $text;
			    break;
			case 'image':
			    return '收到图片消息';
			    break;
			case 'voice':
			    return '收到语音消息';
			    break;
			case 'video':
			    return '收到视频消息';
			    break;
			case 'location':
			    return '收到坐标消息';
			    break;
			case 'link':
			    return '收到链接消息';
			    break;
			// ... 其它消息
			default:
			    return '收到其它消息';
			    break;
    }
        });

        Log::info('return response.');
        return $wechat->server->serve();
    }
	public function getMonitorData($wallet)
        {
                $monitor_data = MonitorData::where('wallet',$wallet)->get();
                $setting = WalletSetting::where('wallet',$wallet)->first();
                $data = ['wallet' => $wallet,
                        'setting' => $setting,
                        'price' => empty($setting)?"{}":json_decode($setting->price),
                        'results' => $monitor_data,
                ];
                return $data;
        }
	public function getWeChatAuthCode()
        {
		$redirect_url = "http://monitor.kaychen.cn/setting";
		$url = sprintf("https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_base&state=0#wechat_redirect",config('wechat.app_id'),$redirect_url);
		return redirect($url);
        }
	public function getWeChatOpenId(Request $request)
        {
		$code = $request->input('code');
		$url = sprintf("https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code",config('wechat.app_id'),config('wechat.secret'),$code);
                return file_get_contents($url);
        }

}
