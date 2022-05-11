<?php
try{

$statusfile = fopen("xiaodan.txt", "r");
$status=fgets($statusfile);
fclose($statusfile);

} catch (\Exception $e){
 exit("file not found");
}

//echo $status;

try{
$st=json_decode($status,true);
}catch(\Exception $e){
 exit("no data at this time");
}


//echo var_dump($st);

echo "<!DOCTYPE HTML>\n";
echo "<html><head><style>table,td{border:1px solid #ccc;border-spacing:0;}</style></head><body>\n";
echo "<table>\n";
echo "<tr><td style='border-bottom:3px solid #ccc'>devid</td><td style='border-bottom:3px solid #ccc'>设备id</td><td style='border-bottom:3px solid #ccc'>".$st["devid"]."</td></tr>";
echo "<tr><td>temperature</td><td>温度</td><td>".$st["temperature"]."°C（与实际值有误差）</td></tr>";
echo "<tr><td>humidity</td><td>湿度</td><td>".$st["humidity"]."%（与实际值有误差）</td></tr>";
echo "<tr><td>PM25</td><td>细颗粒物</td><td>".$st["PM25"]."mcg/m<sup>3</sup>（AQI=".calculateAQI(0,$st["PM25"])."）</td></tr>";
echo "<tr><td>pm10</td><td>大颗粒物</td><td>".$st["pm10"]."</td></tr>";
echo "<tr><td>co2</td><td>二氧化碳浓度</td><td>".$st["co2"]."ppm</td></tr>";
echo "<tr><td>voc</td><td>挥发性有机物</td><td>".$st["voc"]."</td></tr>";
echo "<tr><td>run_status</td><td>运行模式</td><td>".$st["run_status"]."</td></tr>";
echo "<tr><td>range</td><td>运行档位</td><td>".calcstatus($st["range"])."</td></tr>";
echo "<tr><td style='border-bottom:3px solid #ccc'>uv_status</td><td style='border-bottom:3px solid #ccc'>紫外线灯状态</td><td style='border-bottom:3px solid #ccc'>".$st["uv_status"]."</td></tr>";
echo "<tr><td>lock_status</td><td>电源按键锁定状态</td><td>".$st["lock_status"]."</td></tr>";
echo "<tr><td>auto_sleep</td><td>光线变暗时自动进入睡眠模式</td><td>".$st["auto_sleep"]."</td></tr>";
echo "<tr><td>prompt_tone</td><td>操作提示音</td><td>".$st["prompt_tone"]."</td></tr>";
echo "<tr><td>powerlight</td><td>光线变暗时关闭电源指示灯</td><td>".$st["powerlight"]."（0表示关，1表示不关）</td></tr>";
echo "<tr><td>run_favor</td><td>自动模式运行偏好</td><td>". favorstatus($st["run_favor"])."</td></tr>";
echo "<tr><td>night_light_status</td><td>环形灯状态</td><td>".$st["night_light_status"]."</td></tr>";
echo "<tr><td style='border-bottom:3px solid #ccc'>power_button_mode</td><td style='border-bottom:3px solid #ccc'>电源键功能</td><td style='border-bottom:3px solid #ccc'>".powermode($st["power_button_mode"])."</td></tr>";
echo "<tr><td>auto_clean</td><td>自动清洁状态</td><td>".$st["auto_clean"]."</td></tr>";
echo "<tr><td>fwver</td><td>固件版本</td><td>".$st["fwver"]."</td></tr>";
echo "<tr><td>rssi</td><td>网络信号强度</td><td>".$st["rssi"]."</td></tr>";
echo "</table>\n";
echo "</body></html>";



//运行状态
function calcstatus($range) {
if ($range==0){return "关闭";}
if ($range==1){return "一档";}
if ($range==2){return "二档";}
if ($range==3){return "三档";}
if ($range==4){return "四档";}
if ($range==5){return "飓风（噪音注意）";}
}

function favorstatus($favor){
if ($favor==1){return "安静（不会超过三档风速运行";}
if ($favor==2){return "平衡（寻求净化速度与噪音间的平衡";}
if ($favor==3){return "高效（以最快速度净化空气为原则运行";}
}

function powermode($mode){
if ($mode==0){return "在待机、自动模式和手动模式（一至五档）间切换";}
if ($mode==1){return "在待机与自动模式间切换";}
}


 /*
  * 计算空气AQI值
  */
    function calculateAQI($PM10=0,$PM25=0,$CO=0,$SO2=0,$NO2=0,$O3=0){
        //参数区间
        $PM10_arr = [0,50,150,250,350,420,500,600];
        $PM25_arr = [0,35,75,115,150,250,350,500];
        $CO_arr = [0,2,4,14,24,36,48,60];
        $SO2_arr = [0,150,500,650,800];
        $NO2_arr = [0,50,150,475,800,1600,2100,2620];
        $O3_arr = [0,100,160,215,265,800];
        //获取每个指数的AQI
        $arr = array();
        $arr['PM10'] = interval($PM10_arr,$PM10,8);
        $arr['PM25'] = interval($PM25_arr,$PM25,8);
        $arr['CO'] = interval($CO_arr,$CO,8);
        $arr['SO2'] = interval($SO2_arr,$SO2,5);
        $arr['NO2'] = interval($NO2_arr,$NO2,8);
        $arr['O3'] = interval($O3_arr,$O3,6);
        //结果排序
        $AQI = max($arr);
        //包装、处理返回值
        return $AQI;
    }
 
    //获取指数所处区间
    function interval($arr,$val,$max){
        $IAQI_arr = [0,50,100,150,200,300,400,500];//空气质量分指数
        $result = 0;
        for($i=0;$i<$max;$i++){
            if($i<$max-1){
                if($arr[$i]<$val && $val<=$arr[$i+1]){
                    $ihigh = $IAQI_arr[$i+1];
                    $ilow = $IAQI_arr[$i];
                    $high = $arr[$i+1];
                    $low = $arr[$i];
                    $result = calculate($ihigh,$ilow,$high,$low,$val);
                }
            }
        }
        return $result;
    }
    
    function calculate($ihigh,$ilow,$high,$low,$value){
        $AQI = ($ihigh - $ilow) * ($value - $low) / ($high - $low)  + $ilow;
        return round($AQI);
    }


?>
