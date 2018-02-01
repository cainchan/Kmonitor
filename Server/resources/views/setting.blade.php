<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>用户设置</title>
   </head>
    <body>
        <div class="flex-center position-ref full-height">
	<form method="POST" action="/api/savePool">{{ csrf_field() }}
	矿池设置:<br>
	@if ($setting)
	<input type="text"  name="wallet" value="{{ $setting->wallet }}">
	@else
	<input type="text"  name="wallet" value="">
	@endif
	<select name="pool">
	  <option value ="https://www.f2pool.com">F2Pool</option>
	</select>
	<select name="coin">
	  <option value ="eth">ETH</option>
	</select>
	<input type="submit" value="保存">
	</form>
	<br>
       </div>
    </body>
</html>
