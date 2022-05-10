<?php
$statusfile = fopen("xiaodan.txt", "r");
$status=fgets($statusfile);
fclose($statusfile); 

$st=json_decode($status,true);

switch($_GET['a'])
{
  case 'getDevList':
    echo '{"result":"0","message":"ok","content":[{"devid":"'.$st['devid'].'","devname":"倒闭的小蛋","online":1,"pm25":'.$st['PM25'].',"status":'.getstatus($st['run_status']).'}]  }';
    die;
  case 'get_devstatus':
    echo '{"result":"1","message":"get_devstatus","content":""}';
    die;
  case 'startSync':
    echo '{"result":"1","message":"startSync","content":""}';
    die;
  case 'get_status_sys':
    echo '{"result":"1","message":"get_status_sys","content":""}';
    die;
   default:
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
   return 3;//固件更新模式
}
}
