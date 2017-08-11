<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Miner Monitor Data</title>
   </head>
    <body>
        <div class="flex-center position-ref full-height">
	<form method="POST" action="/api/saveWalletSetting/{{ $wallet }}">{{ csrf_field() }}
	离线通知Email:<br>
	@if ($setting)
	<input type="text"  name="email" value="{{ $setting->email }}">
	@else
	<input type="text"  name="email" value="">
	@endif
	<input type="submit" value="保存">
	</form>
	<br>
	<table border="1">
	<thead>
		<tr>
		<th>Miner</th>
		<th>更新时间</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($results as $miner)
		<tr>
    		<td>{{ $miner->miner }}</td>
    		<td>{{ $miner->updated_at }}</td>
		</tr>
		@endforeach
		@if ($setting)
		@if ($setting->balance)
		<tr>
		<th>未付余额</th>
		<th>更新时间</th>
		</tr>
		<tr>
		<td>{{$setting->balance}}</td>
		<td>{{$setting->updated_at}}</td>
		</tr>
		<tr>
		<th>最近支付</th>
		<th>支付时间</th>
		</tr>
		<tr>
		<td>{{$setting->last_paid_balance}}</td>
		<td>{{$setting->last_paid_date}}</td>
		</tr>	
		@endif
		@endif
	</tbody>
	</table>
	<br>
       </div>
    </body>
</html>
