<?php

namespace App\Http\Controllers;

use App\MonitorData;
use Illuminate\Http\Request;

class MonitorController extends Controller
{
	public function getMonitorData($wallet)
	{
		$ret = MonitorData::where('wallet',$wallet)->get();
		return response()->json($ret);
	}
    public function pushMonitorData($wallet,$miner)
    {
	$result = ['result' => '', 'error_msg' => '', 'code' => 1];
	// 通过wallet+miner查询数据
	$ret = MonitorData::where('wallet',$wallet)->where('miner',$miner)->first();
	if (empty($ret)){
		$data = new MonitorData;
		$data->wallet = $wallet;
		$data->miner = $miner;
		$data->save();
	}else{
		// 更新时间
		$ret->updated_at = date('Y-m-d H:i:s');
		$ret->save();
	}
		$result['result'] = 'success';
        return response()->json($result);
    }
}
