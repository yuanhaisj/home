<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>足球竞猜列表页面</title>
</head>
<body>
	<table style="width:300px;">
       <thead><tr><th>球队</td><td>操作</th></tr></thead>
       <tbody style="text-align: center;">
       	 @if(!empty($list))
	       @foreach($list as $key=>$value)
	       <tr style="height: 35px;line-height: 35px;">
		       <td>{{$value['team_a']}} VS {{$value['team_b']}}</td>
		       <td>
		          @if(strtotime($value['end_at']) > time())
		            <a href="/study/guess/guess?id={{$value['id']}}&user_id={{$user_id}}">竞猜</a>
		          @else
		            <a href="/study/guess/result?id={{$value['id']}}&user_id={{$user_id}}">查看结果</a>
		          @endif
		       </td>
	       </tr>
	       @endforeach
       @endif
       </tbody>
	</table>
</body>
</html>
