<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8"></head>
<title>小蛋空气净化器</title>
<style>table,td{border:1px solid #ccc;border-spacing:0} .split td{border-bottom:3px solid #ccc} #process{background-color:#aaa;float:left;width:100%;height:10px}</style>
<script language="javascript" src="http://lib.sinaapp.com/js/jquery/3.1.0/jquery-3.1.0.min.js"></script>
<script language="javascript">
var favor=["","安静（不会超过三档风速运行）","平衡（寻求净化速度与噪音间的平衡）","高效（以最快速度净化空气为原则运行）"];
var mode=["在待机、自动模式和手动模式（一至五档）间切换","在待机与自动模式间切换"];
var range=["关闭","一档","二档","三档","四档","飓风（噪音注意）"];
var light={"bl":"自动（当环境变暗时关闭）","open":"打开","close":"关闭"};
var power=["是","否"];
var tone=["否","是"];
var button={"close":"否","open":"是"};
var count=30;

$(document).ready(function() {
    setInterval( update, 30000 );
    setInterval( process, 1000 );
    update();
});

function update(){
    $.getJSON("xiaodan.txt","",function(data){
        $("#devid").html(data.devid);
        $("#temperature").html(data.temperature);
        $("#humidity").html(data.humidity);
        $("#PM25").html(data.PM25);
        $("#pm10").html(data.pm10);
        $("#co2").html(data.co2);
        $("#voc").html(data.voc);
        $("#run_status").html(data.run_status);
        $("#range").html(range[data.range]);
        $("#uv_status").html(data.uv_status);

        $("#lock_status").html(button[data.lock_status]);
        $("#auto_sleep").html(tone[data.auto_sleep]);
        $("#prompt_tone").html(tone[data.prompt_tone]);
        $("#powerlight").html(power[data.powerlight]);
        $("#run_favor").html(favor[data.run_favor]);
        $("#night_light_status").html(light[data.night_light_status]);
        $("#power_button_mode").html(mode[data.power_button_mode]);
        $("#auto_clean").html(data.auto_clean);
        $("#fwver").html(data.fwver);
        $("#rssi").html(data.rssi);
})
}

function process(){
    count-=1;
    $("#process").css("width", Math.floor($("#timecount")[0].clientWidth*count/30)-2+"px");
    if (count<=1){count=31}
}
</script>
 
</head>
<body>
 
<table>
<tr class="split"><td>devid</td><td>设备id</td><td><span id="devid"></span></td></tr>
<tr><td>temperature</td><td>温度</td><td><span id="temperature"></span>°C（与实际值有误差）</td></tr>
<tr><td>humidity</td><td>湿度</td><td><span id="humidity"></span>%（与实际值有误差）</td></tr>
<tr><td>PM25</td><td>细颗粒物</td><td><span id="PM25"></span>mcg/m<sup>3</sup>（AQI=19）</td></tr>
<tr><td>pm10</td><td>大颗粒物</td><td><span id="pm10"></span></td></tr>
<tr><td>co2</td><td>二氧化碳浓度</td><td><span id="co2"></span>ppm</td></tr>
<tr><td>voc</td><td>挥发性有机物</td><td><span id="voc"></span></td></tr>
<tr><td>run_status</td><td>运行模式</td><td><span id="run_status"></span></td></tr>
<tr><td>range</td><td>运行档位</td><td><span id="range"></span></td></tr>
<tr class="split"><td>uv_status</td><td>紫外线灯状态</td><td><span id="uv_status"></span></td></tr>
<tr><td>lock_status</td><td>电源按键锁定状态</td><td><span id="lock_status"></span></td></tr>
<tr><td>auto_sleep</td><td>光线变暗时自动进入睡眠模式</td><td><span id="auto_sleep"></span></td></tr>
<tr><td>prompt_tone</td><td>操作提示音</td><td><span id="prompt_tone"></span></td></tr>
<tr><td>powerlight</td><td>光线变暗时关闭电源指示灯</td><td><span id="powerlight"></span></td></tr>
<tr><td>run_favor</td><td>自动模式运行偏好</td><td><span id="run_favor"></span></td></tr>
<tr><td>night_light_status</td><td>环形灯状态</td><td><span id="night_light_status"></span></td></tr>
<tr class="split"><td>power_button_mode</td><td>电源键功能</td><td><span id="power_button_mode"></span></td></tr>
<tr><td>auto_clean</td><td>自动清洁状态</td><td><span id="auto_clean"></span></td></tr>
<tr><td>fwver</td><td>固件版本</td><td><span id="fwver"></span></td></tr>
<tr><td>rssi</td><td>网络信号强度</td><td><span id="rssi"></span></td></tr>
<tr><td colspan="3" id="timecount"><span id="process"></span></td></tr>
</table>
 
</body></html>
