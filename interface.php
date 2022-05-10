<?php
$statusfile = fopen("xiaodan.txt", "r");
$status=fgets($statusfile);
fclose($statusfile); 

$st=json_decode($status,true);

switch($_GET['a'])
{
  case 'getDevList':
    echo '{"result":"0","message":"ok","content":[{"devid":"'.$st['devid'].'","devname":"倒闭的小蛋","online":1,"pm25":'.$st['PM25'].',"range":'.$st['range'].'}]  }';
  default:
    echo '{"result":"1","message":"not ok"}';
}

?>
