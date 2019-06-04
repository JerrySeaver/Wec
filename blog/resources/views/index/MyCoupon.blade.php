
<html>
<hr>
@foreach($array as $k => $v)
<table border="1">
<p>{{$v['c_name']}}</p><br>
<p>{{$v['conditions'].$v['amount']}}</p><br>
<p>时间：{{date('Y-m-d h:i:s',$v['time'])."---过期时间".date('Y-m-d h:i:s',$v['time']+60*60*24*7)}}</p>
</table>
<hr>
@endforeach
</html>