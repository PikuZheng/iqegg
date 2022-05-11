<?php
$statusfile = fopen("xiaodan.txt", "r");
$status=fgets($statusfile);
fclose($statusfile); 

$st=json_decode($status,true);

$_v=array(
"no"=>0,"close"=>0,"off"=>0,"unlock"=>0,"complex"=>0,
"yes"=>1,"auto"=>1,"open"=>1,"on"=>1,"lock"=>1,"aj"=>1,"simple"=>1,
"manual"=>2,"bl"=>2,"ph"=>2,
"gx"=>3,
"A"=>1,"B"=>2,"C"=>3,"D"=>4,"E"=>5
);

switch($_GET['a'])
{
  case 'getDevList': //设备列表（打开程序默认页
    echo '{"result":"0","message":"ok","content":[{"devid":"'.$st['devid'].'","m_code":"'.$st['devid'].'","devname":"倒闭的小蛋","online":1,"pm25":'.$st['PM25'].',"status":'.$_v[$st['run_status']].',"sort":1}]  }';
    die;
  case 'get_devstatus': //设备状态详情页
    echo '{"result":"0","message":"ok","content":{"PM25":'.$st['PM25'].',"auto_clean":'.$_v[$st['auto_clean']].',"co2":"'.$st['co2'].'","hcho":"0","humidity":"'.$st['humidity'].'","lock_status":'.$_v[$st['lock_status']].',"model_type":0,"night_light_model":1,"night_light_status":'.$_v[$st['night_light_status']].',"range":'.$st['range'].',"run_favor":'.$st['run_favor'].',"run_statu":'.$_v[$st['run_status']].',"temperature":"'.$st['temperature'].'","uv_statu":'.$_v[$st['uv_status']].',"voc":"'.$st['voc'].'"}  }';
    die;
  case 'startSync': //应该是触发一次设备上报数据 但不知道对应mqtt指令
    echo '{"result":"0","message":"not ok","content":"111" }';
    die;
  case 'get_status_index': //设备状态详情页 内容与get_devstatus相同
    echo '{"result":"0","message":"ok","content":{"PM25":'.$st['PM25'].',"auto_clean":'.$_v[$st['auto_clean']].',"co2":"'.$st['co2'].'","hcho":"0","humidity":"'.$st['humidity'].'","lock_status":'.$_v[$st['lock_status']].',"model_type":0,"night_light_model":1,"night_light_status":'.$_v[$st['night_light_status']].',"range":'.$st['range'].',"run_favor":'.$st['run_favor'].',"run_statu":'.$_v[$st['run_status']].',"temperature":"'.$st['temperature'].'","uv_statu":'.$_v[$st['uv_status']].',"voc":"'.$st['voc'].'"}  }';
    die;
   
  case 'get_city': //设备详情右上角地区
    echo '{"result":"0","message":"not ok","content":{"city":"火星","pm25":"0"} }';
    die;
  case 'last_day_report': //每天首次登陆显示前一天统计数据
    echo '{"result":"0","message":"ok","content":{"maxPm25":0,"minPm25":0,"pm25Weight":0}}';
    die;

  case 'operate': //关闭和自动之间的切换
    sendmqtt($_POST['devid'], 'mode', $_v[$_POST["mode"]] );
    echo '{"result":"0","message":"ok","content":""}';
    die;
  case 'change_mode': //手动和自动之间的切换 与operate相同
    sendmqtt($_POST['devid'], 'mode', $_v[$_POST["mode"]] );
    echo '{"result":"0","message":"ok","content":""}';
    die;
  case 'shift': //手动模式的运行速度
    sendmqtt($_POST['devid'], 'shift', $_v[$_POST['range']] );
    echo '{"result":"0","message":"ok","content":""}';
    die;
  case 'uv_operate': //紫外线灯
    sendmqtt($_POST['devid'], 'uvstatus', $_v[$_POST['status']] );
    echo '{"result":"0","message":"ok","content":""}';
    die;

  case 'auto_clean': //初级滤网自清洁 慎用
    sendmqtt($_POST['devid'], 'auto_clean', $_v[$_POST['operate']] );
    echo '{"result":"0","message":"ok","content":""}';
    die;

  case 'child_lock': //锁定机身电源按键
    sendmqtt($_POST['devid'], 'childlock', $_v[$_POST['operate']] );
    echo '{"result":"0","message":"ok","content":""}';
    die;
  case 'set_auto_sleep': //光线变暗时进入睡眠模式
    sendmqtt($_POST['devid'], 'auto_sleep', $_v[$_POST['operate']] );
    echo '{"result":"0","message":"ok","content":""}';
    die;
  case 'set_prompt_tone': //操作提示音设置
    sendmqtt($_POST['devid'], 'prompt_tone', $_v[$_POST['operate']] );
    echo '{"result":"0","message":"ok","content":""}';
    die;
  case 'power_light': //光线变暗时关闭电源灯
    sendmqtt($_POST['devid'], 'powerlight', $_v[$_POST['operate']] );
    echo '{"result":"0","message":"ok","content":""}';
    die;

  case 'run_favor': //自动运行偏好
    sendmqtt($_POST['devid'], 'favor', $_v[$_POST['operate']] );
    echo '{"result":"0","message":"ok","content":""}';
    die;
  case 'night_light': //环形灯带设置
    sendmqtt($_POST['devid'], 'nightlight', $_v[$_POST['operate']] );
    echo '{"result":"0","message":"ok","content":""}';
    die;
  case 'set_power_button_mode': //机身电源键功能
    sendmqtt($_POST['devid'], 'power_button_mode', $_v[$_POST['operate']] );
    echo '{"result":"0","message":"ok","content":""}';
    die;

  case 'get_status_sys': //当前设置查询
    $powerlight=$st['powerlight']?0:1; //在app呈现上电源灯开关是反的
    echo '{"result":"0","message":"ok","content":{"auto_sleep":'.$st['auto_sleep'].',"firmware_version":'.$st['fwver'].',"is_update":0,"isshowctrl":0,"lock_status":'.$_v[$st['lock_status']].',"new_version":0,"night_light_model":1,"night_light_status":'.$_v[$st['night_light_status']].',"power_button_mode":'.$st['power_button_mode'].',"power_light":'.$powerlight.',"privctrl":0,"prompt_tone":'.$st['prompt_tone'].',"run_favor":'.$st['run_favor'].',"update_content":"再也不会有更新了"}  }';
    die;

  case 'login': //不登陆就不让用 登啥都成功
    echo '{"result":0,"message":"ok","content":{"info":"","phone":"'.$_REQUEST['phone'].'","uid":1}}';
    die;
 
  default:
//用于查看究竟post了什么数据
//$l=fopen("xiaodan_client.txt","w");
//fwrite($l,json_encode($_REQUEST));
//fclose($l);
    echo '{"result":"1","message":"'.$_GET['a'].'"}';
}

function sendmqtt($devid,$key,$value)
{
  $mqttserver="http://mqttserver:8081/api/v4/mqtt/publish";
  $mqttuser="admin";
  $mqttpass="public";

  $ch=curl_init();
  curl_setopt($ch, CURLOPT_URL, $mqttserver);
  curl_setopt($ch, CURLOPT_POSTFIELDS,'{"topic":"iqegg/'.$devid.'","clientid":"mosqsub/'.$devid.'","payload":{"key":"'.$key.'","value":'.$value.'}}' );
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_USERPWD, $mqttuser.":".$mqttpass);
  curl_setopt($ch, CURLOPT_USERAGENT, "");
  $data = curl_exec($ch);
  curl_close($ch);
 
  return $data;
}
?>
