<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>足球竞猜结果页面</title>
</head>
<body style="width: 100%;">
	
   <div style="margin:0px auto;">
      
        {{csrf_field()}}
    
      	<table style="width: 100%;border:#d4d4d4 1px solid">
         <tr><td style="width:300px;text-align:center;font-weight:bold;">查看结果</td></tr>
         <tr><td style="width:300px;text-align:center;">对阵结果：
         {{$info['team_a']}}
         <font color="#ff0000">@if($info['result'] == 1) 胜 @elseif($info['result'] == 2) 平 @else 负 @endif</font>
         {{$info['team_b']}}
         </td></tr>
         @if(!empty($first))
         <tr><td style="width:300px;text-align:center;">你的竞猜：
            {{$info['team_a']}}
            <font color="#ff0000">@if($first->g_result == 1) 胜 @elseif($first->g_result == 2) 平 @else 负 @endif</font>
            {{$info['team_b']}}
         </td></tr>
         <tr>
            <td style="width:300px;text-align:center;">
                @if($first->g_result == $info['result']) 恭喜猜中 @else 抱歉没猜中 @endif
            </td>
         </tr>
         @else
          <tr>
            <td style="width:300px;text-align:center;">
                结果：你没有参与竞猜
            </td>
         </tr>
         @endif
        </table>
     
   </div>

</body>
</html>