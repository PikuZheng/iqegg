<?php
$statusfile = fopen("xiaodan.txt", "r");
$status=fgets($statusfile);
fclose($statusfile); 

$st=json_decode($status,true);
switch($_GET['a'])
{
  case 'getDevList':
    echo '{"result":"0","message":"ok","content":[{"devid":"'.$st['devid'].'","m_code":"'.$st['devid'].'","devname":"倒闭的小蛋","online":1,"pm25":'.$st['PM25'].',"status":'.getstatus($st['run_status']).',"sort":1}]  }';
    die;

  case 'get_devstatus':
    echo '{"result":"0","message":"ok","content":{"PM25":'.$st['PM25'].',"auto_clean":'.getstatus($st['auto_clean']).',"co2":"'.$st['co2'].'","hcho":"0","humidity":"'.$st['humidity'].'","lock_status":'.getstatus($st['lock_status']).',"model_type":0,"night_light_model":1,"night_light_status":'.getstatus($st['night_light_status']).',"range":'.$st['range'].',"run_favor":'.$st['run_favor'].',"run_statu":'.getstatus($st['run_status']).',"temperature":"'.$st['temperature'].'","uv_statu":'.getstatus($st['uv_status']).',"voc":"'.$st['voc'].'"}  }';
    die;

  case 'startSync':
    echo '{"result":"0","message":"not ok","content":"111" }';
    die;

  case 'get_status_index':
    echo '{"result":"0","message":"ok","content":{"PM25":'.$st['PM25'].',"auto_clean":'.getstatus($st['auto_clean']).',"co2":"'.$st['co2'].'","hcho":"0","humidity":"'.$st['humidity'].'","lock_status":'.getstatus($st['lock_status']).',"model_type":0,"night_light_model":1,"night_light_status":'.getstatus($st['night_light_status']).',"range":'.$st['range'].',"run_favor":'.$st['run_favor'].',"run_statu":'.getstatus($st['run_status']).',"temperature":"'.$st['temperature'].'","uv_statu":'.getstatus($st['uv_status']).',"voc":"'.$st['voc'].'"}  }';
    die;
   
   case 'get_city':
    echo '{"result":"0","message":"not ok","content":{"city":"火星","pm25":"0"} }';
    die;

  case 'last_day_report':
    echo '{"result":"0","message":"ok","content":{"maxPm25":0,"minPm25":0,"pm25Weight":0}}';
    die;

  case 'get_status_sys':
    echo '{"result":"0","message":"ok","content":{"auto_sleep":'.$st['auto_sleep'].',"firmware_version":'.$st['fwver'].',"is_update":0,"isshowctrl":0,"lock_status":'.getstatus($st['lock_status']).',"new_version":0,"night_light_model":1,"night_light_status":'.getstatus($st['night_light_status']).',"power_button_mode":'.$st['power_button_mode'].',"power_light":'.$st['powerlight'].',"privctrl":0,"prompt_tone":'.$st['prompt_tone'].',"run_favor":'.$st['run_favor'].',"update_content":"再也不会有更新了"}  }';
    die;

  case 'login':
    echo '{"result":0,"message":"ok","content":{"info":"","phone":"'.$_REQUEST['phone'].'","uid":1}}';
    die;
 
  default:
//写入文件用于查看究竟post了什么数据
//$l=fopen("xiaodan_client.txt","w");
//fwrite($l,json_encode($_REQUEST));
//fclose($l);
    echo '{"result":"1","message":"'.$_GET['a'].'"}';
}


function getstatus($status)
{
  switch ($status)
  {
   case "auto":
     return 1;
   case "manual":
     return 2;
   case "close":
     return 0;
   default:
     return 3;
  }
}

?>
