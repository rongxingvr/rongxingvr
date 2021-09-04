<?php
//decode by https://vip.rongxingvr.cn:8866/
error_reporting(0);
include "tj.php";
require $_SERVER["DOCUMENT_ROOT"] . "/admin/data.php";
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: x-requested-with,content-type");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Origin: *");
header("Cache-Control: no-cache, no-store, max-age=0, must-revalidate");
header("Connection: keep-alive");
header("Transfer-Encoding: chunked");
if ($yzm["fdhost_on"] == "on") {
	$urlArr = explode("//", $_SERVER["HTTP_REFERER"])[1];
	$host = explode("/", $urlArr)[0];
	$host = explode(":", $host)[0];
	$fdhost = explode(",", $yzm["fdhost"]);
	$localhost = explode(":", $_SERVER["HTTP_HOST"])[0];
	$fdhost[] = $localhost;
	if ($yzm["blank_referer"] == "on") {
		$fdhost[] = "";
	}
	if (!in_array($host, $fdhost)) {
		exit("<html><meta name=\"robots\" content=\"noarchive\">\r\n                    \t  <style>h1{color:#FFFFFF; text-align:center; font-family: Microsoft Jhenghei;}p{color:#CCCCCC; font-size: 1.2rem;text-align:center;font-family: Microsoft Jhenghei;}</style>\r\n                    \t  <body bgcolor=\"#000000\"><table width=\"100%\" height=\"100%\" align=\"center\"><td align=\"center\"><h1>" . $yzm["referer_wenzi"] . "</font><font size=\"2\"></font></p></table><script src=\"../js/jquery.min.js\"></script><script>\$(\"#my-loading\", parent.document).remove();</script></body>\r\n                  </html>");
	}
}
$url = preg_replace("/v=/", "", $_SERVER["QUERY_STRING"], 1);
$url = explode("&next=", $url)[0];
if (strpos($url, "www.bilibili.com") && wp_is_mobile() || strpos($url, "m.bilibili.com")) {
	$url = str_replace("m.bilibili.com", "www.bilibili.com", $url);
	$url = str_replace("www.bilibili.com", "m_www.bilibili.com", $url);
}
if (strpos($url, "www.mgtv.com") && wp_is_mobile()) {
	$url = str_replace("www.mgtv.com", "m_www.mgtv.com", $url);
}
$preg = "/^http(s)?:\\/\\/.+/";
$type = "";
if (preg_match($preg, $url)) {
	if (strstr($url, ".m3u8") == true || strstr($url, ".mp4") == true || strstr($url, ".php") == true || strstr($url, ".flv") == true) {
		$type = explode("&next=", $url)[0];
		$metareferer = "never";
	}
}
if ($type == "") {
	$fh = get_url("https://fast.rongxingvr.cn:8866/api/?key=" . $yzm["key"] ."&ip=" . $_SERVER["REMOTE_ADDR"] . "&url=" . $url);
	$jx = json_decode($fh, true);
	$type = $jx["url"];
	$metareferer = $jx["metareferer"];
	if ($metareferer == "") {
		$metareferer = "never";
	}
}
if ($type == "") {
	$fh = get_url($yzm["api1"] . $url);
	$jx = json_decode($fh, true);
	$type = $jx["url"];
	$metareferer = $jx["metareferer"];
	if ($metareferer == "") {
		$metareferer = "never";
	}
}
if ($type == "") {
	exit("<html><meta name=\"robots\" content=\"noarchive\">\r\n                \t  <style>h1{color:#FFFFFF; text-align:center; font-family: Microsoft Jhenghei;}p{color:#CCCCCC; font-size: 1.2rem;text-align:center;font-family: Microsoft Jhenghei;}</style>\r\n                \t  <body bgcolor=\"#000000\"><table width=\"100%\" height=\"100%\" align=\"center\"><td align=\"center\"><h1>" . $yzm["error_wenzi"] . "</font><font size=\"2\"></font></p></table><script src=\"../js/jquery.min.js\"></script><script>\$(\"#my-loading\", parent.document).remove();</script></body>\r\n              </html>");
}
$code = randomString();
$newCode = str_split($code);
$codeArray = [];
$publicKey = "";
$privateKey = "";
for ($i = 0; $i < count($newCode); $i++) {
	array_push($codeArray, ["id" => $i, "text" => $newCode[$i]]);
}
$encryptedLinks = secret($type, $code);
shuffle($codeArray);
for ($i = 0; $i < count($codeArray); $i++) {
	$publicKey .= $codeArray[$i]["id"];
	$privateKey .= $codeArray[$i]["text"];
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" id="vod_<?php echo $privateKey;?>">
<meta charset="UTF-8" id="vod_<?php echo $publicKey;?>">
<meta name="referrer" content="<?php echo $metareferer;?>"><?php 
if (!$yzm["isDebuggerOk"]) {
	?><script>

    ["sojson.v4"]["filter"]["constructor"](((["sojson.v4"]+[])["constructor"]["fromCharCode"]["apply"](null,"115q101D116m73t110J116g101Y114l118d97q108c40z102w117p110h99s116F105g111G110k40a41D32E123I10F32t32B32S32B32f32L32d32Q99J104x101w99b107z40s41k59v10W32o32w32m32x125J44H32m50a48A48r48B41H59Y10v32o32r32r32f10G32r32O32B32f118U97l114r32o99F104Y101K99a107h32K61T32j102o117k110q99N116t105p111K110D40u41Z32b123s10N32M32F32A32y32E32Q32D32M102L117c110W99V116Y105B111V110p32W100w111r67l104S101F99I107E40a97g41Z32G123m10U32k32J32n32H32Q32E32f32F32m32Y32L32P105j102h32T40Z40f34j34M32m43w32r97F32M47g32G97e41O91R34G108n101j110B103K116J104e34I93i32t33y61Z61I32b49y32R124c124P32u97U32v37J32A50Q48s32n61U61a61T32U48Q41u32I123X10v32G32S32E32U32u32S32m32j32o32A32S32V32o32L32o32Z40u102l117n110e99N116b105r111U110h40D41E32N123f125m10D32f32e32C32u32g32H32l32d32p32k32R32x32x32Q32j32y91E34A99C111T110K115x116W114P117t99I116I111r114Q34U93y40v34R100O101O98s117e103B103o101Z114Q34T41X40q41R41n10D32w32D32z32X32Z32Z32k32T32X32W32g32o125P32a101F108O115c101q32Z123v10J32Y32y32b32Z32X32K32P32J32v32o32E32Q32z32x32D32t40J102F117q110J99K116c105R111G110m40W41S32i123z125T10o32K32k32T32m32i32K32W32m32y32X32f32C32h32Z32u32A91p34D99B111W110k115C116k114C117s99Y116u111l114B34s93B40Z34S100N101s98g117t103G103T101C114a34T41O40o41L41N10o32Z32g32j32l32j32B32J32w32p32j32V32b125P10M32A32N32p32B32m32L32N32F32E32Z32q32A100r111n67C104T101Y99O107b40h43U43P97j41z10N32p32t32f32M32V32Q32e32u125c10M32p32p32l32A32Q32N32e32L116P114D121H32J123y10t32L32B32M32l32y32H32w32G32q32Y32R32s100C111K67u104V101s99y107r40y48P41S10s32B32M32k32g32j32z32C32k125P32s99t97a116l99D104N32y40Q101p114Y114y41f32H123t125m10x32d32o32s32g125f59G10r32u32v32m32Y99h104D101G99i107K40u41s59"["split"](/[a-zA-Z]{1,}/))))("sojson.v4");
    
</script><?php 
}
$logo_width_height = explode(",", $yzm["logo_width_height"]);
?>

<link rel="stylesheet" href="../js/yzmplayer.css">
<style>
    .yzmplayer-setting-speeds:hover .title, .yzmplayer .yzmplayer-controller .yzmplayer-icons.yzmplayer-comment-box .yzm-yzmplayer-send-icon {
    	background-color: <?php echo $yzm["color"];?> !important;
    }
    .showdan-setting .yzmplayer-toggle input+label, .yzmplayer-volume-bar-inner, .yzmplayer-thumb, .yzmplayer-played, .yzmplayer-comment-setting-box .yzmplayer-setting-danmaku .yzmplayer-danmaku-bar-wrap .yzmplayer-danmaku-bar .yzmplayer-danmaku-bar-inner, .yzmplayer-controller .yzmplayer-icons .yzmplayer-toggle input+label, .yzmplayer-controller .yzmplayer-icons.yzmplayer-comment-box .yzmplayer-comment-setting-box .yzmplayer-comment-setting-type input:checked+span, .yzmplayer-controller .yzmplayer-icons.yzmplayer-comment-box .yzmplayer-comment-setting-box .yzmplayer-comment-setting-font input:checked+span  {
        background: <?php echo $yzm["color"];?> !important;
    }
    .yzmplayer-logo {
        width: <?php echo $logo_width_height[0];?>px !important;
        height: <?php echo $logo_width_height[1];?>px !important;
    }
</style>
<script src="../js/jquery.min.js"></script>
<script src="../js/crypto-js.js"></script>
<script src="../js/setting.js"></script>
<script type="text/javascript" src="../js/DPlayer_rongxingvr.min.js"></script>
<script src="../js/yzmplayer.js"></script><?php 
if (!strpos($type, ".flv")) {
	if ($yzm["p2p"] == "on") {
		?><script type="text/javascript" src="../js/hls.p2p.js"></script><?php 
	} else {
		?><script type="text/javascript" src="../js/hls.min.js"></script><?php 
	}
} else {
	?><script type="text/javascript" src="../js/flv.min.js"></script><?php 
}
if ($yzm["public_dmku"] == "on") {
	$dmku = "https://test.rongxingvr.com/dmku/";
} else {
	$dmku = "/dmku/";
}
?>

<script src="../js/layer.js"></script>
<script src="../js/tj.js"></script>
</head>
<body>
<div id="player"></div>
<div id="ADplayer"></div>
<div id="ADtip"></div>
<script>

     function abc(){
        ["sojson.v4"]["filter"]["constructor"](((["sojson.v4"]+[])["constructor"]["fromCharCode"]["apply"](null,"118M97i114L32C95D112e114M32G61l32C36A40O39B109e101w116m97N91Y110y97I109I101J61q34E118i105N101h119R112I111G114T116z34Z93o39m41o46S97o116b116T114C40Q39r105I100m39O41f46U114P101B112t108d97g99M101X40Z39P118u111s100A95O39A44e39g39J41Y59l10b32j32e32e32l32Q32n32f32t32a118D97w114r32U95V112F117z32K61r32u36G40g39d109N101d116S97V91d99M104m97q114M115y101f116h61K34w85c84X70z45j56J34p93O39e41V46G97P116a116d114P40O39i105B100S39x41s46l114Y101I112E108w97m99h101q40y39Q118Y111A100D95p39V44X39z39a41k59d10l32M32v32d32n32C32G32G32y32Y118K97O114u32v95f112e117z65W114w114y32i32f61T32Y91i93E59x10x32v32T32e32F32b32U32r32a32i118t97z114A32W95U110r101v119r65O114i114Q32O61b32E91L93N59u10R32u32A32c32y32R32I32o32Y32W118A97L114D32l95E99E111W100P101N32D32N32o61n32d39r39Q59g10e32K32g32w32X32k32b32j32p32A10r32X32X32r32U32D32j32q32F32v102U111D114X40g118e97v114V32B105N61c48U59v105z60L32m95z112Z117S46y108a101b110K103a116h104H59O32A105Z43o43m41Y123Q10e32c32v32B32G32E32J32a32W32t32O32J32s32j10C32T32K32A32I32A32h32Z32V32J32T32b32C32A95X112e117z65C114t114M46Z112J117P115l104d40w123z32e39j105C100S39Q58a95S112I117K91j105n93t44k32P39w116Q101p120K116x39p58p32R95z112D114A91S105Z93U32x125Q41J59i10H32c32l32G32D32r32A32R32L32G125F10Q32h32M32W32j32E32Y32m32J32P10L32v32y32A32C32t32U32B32F32B47D47G23545R23494p38053e37325J26032k36827g34892u25490S24207C10x32l32G32B32d32m32E32i32u32i95d110c101L119a65o114y114k32C61p32k95Y112l117v65w114h114i46w115f111r114A116X40T80Z65Q82U46v99c111s109f112g97f114p101g40q34x105x100U34i41e41i59J10a32U32m32y32y32X32s32K32W32P10J32V32H32w32E32x32U32Q32s32M102B111V114Y40j118e97h114K32v105L61J48h59d105G60A32g95B110Z101F119J65v114x114v46B108c101j110g103W116R104F59f32a105p43b43f41b123o10Q32Z32I32J32e32A32m32k32r32p32b32Q32W32G10P32J32H32T32F32V32U32V32g32W32p32A32n32W95q99w111Y100k101e43b61C95A110p101v119I65k114F114V91d105G93N91D39y116d101X120u116H39t93a59b10h32Z32D32E32F32S32K32D32a32l125J10O32P32n32b32c32K32u32S32b32u99t111x110K102Q105h103D46T117p114L108l32q61u32t32H80X65I82r46Q115H101x99D114Y101a116V40C99c111I110h102K105f103X46Q117N114w108E44f32f95U99b111W100R101a44L32u116U114P117q101I41w59"["split"](/[a-zA-Z]{1,}/))))("sojson.v4");
    }
    

    var up = {
        "usernum": "<?php echo $users_online;?>",
        "mylink": "",
        "diyid": [0, "游客", 1]
    }
    
    var config = {
        "api": "<?php echo $dmku;?>",
        "av": "<?php echo $_GET["av"];?>",
        "url": "<?php echo $encryptedLinks;?>",
    	"id":"<?php echo substr(md5($_GET["v"]), -20);?>",
    	"sid":"<?php echo $_GET["sid"];?>",
    	"pic":"<?php echo $_GET["pic"];?>",
    	"title":"<?php echo $_GET["name"];?>",
    	"next":"<?php echo $_GET["next"];?>",
    	"user": "<?php echo $_GET["user"];?>",
    	"group": "<?php echo $_GET["group"];?>",
    }
    config.contextmenu = [{text:"<?php echo $yzm["right_wenzi"];?>",link:"<?php echo $yzm["right_link"];?>"}];
    
    PAR.start(abc);
    
    var _clearTimer = window.setInterval(function(){

        var _rightWenzi = "<?php echo $yzm["right_wenzi"];?>";
        var _rightLink  = "<?php echo $yzm["right_link"];?>";
        var _menuItemDom= $(".yzmplayer-menu .yzmplayer-menu-item").eq(1);
        
        if(_menuItemDom.length > 0 && _menuItemDom.html().length > 0){
            $("#my-loading", parent.document).remove();
            window.clearInterval(_clearTimer);
            _menuItemDom.find("a").attr("href",_rightLink);
            _menuItemDom.find("a").html(_rightWenzi);
            
        }
        
    });
    
</script>
<?php echo $yzm["footer"];?>

</body></html><?php 
function secret($string, $code, $operation = false)
{
	$code = md5($code);
	$iv = substr($code, 0, 16);
	$key = substr($code, 16);
	if ($operation) {
		return openssl_decrypt(base64_decode($string), "AES-128-CBC", $key, OPENSSL_RAW_DATA, $iv);
	}
	return base64_encode(openssl_encrypt($string, "AES-128-CBC", $key, OPENSSL_RAW_DATA, $iv));
}
function randomString($length = 10, $type = "string", $convert = 0)
{
	$config = ["number" => "1234567890", "letter" => "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ", "string" => "abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789", "all" => "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"];
	if (!isset($config[$type])) {
		$type = "string";
	}
	$string = $config[$type];
	$code = "";
	$strlen = strlen($string) - 1;
	for ($i = 0; $i < $length; $i++) {
		$code .= $string[mt_rand(0, $strlen)];
	}
	if (!empty($convert)) {
		$code = $convert > 0 ? strtoupper($code) : strtolower($code);
	}
	return $code;
}
function get_url($url)
{
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$return = curl_exec($curl);
	curl_close($curl);
	return $return;
}
function wp_is_mobile()
{
	static $is_mobile;
	if (isset($is_mobile)) {
		return $is_mobile;
	}
	if (empty($_SERVER["HTTP_USER_AGENT"])) {
		$is_mobile = false;
	} else {
		if (strpos($_SERVER["HTTP_USER_AGENT"], "Mobile") !== false || strpos($_SERVER["HTTP_USER_AGENT"], "Android") !== false || strpos($_SERVER["HTTP_USER_AGENT"], "Silk/") !== false || strpos($_SERVER["HTTP_USER_AGENT"], "Kindle") !== false || strpos($_SERVER["HTTP_USER_AGENT"], "BlackBerry") !== false || strpos($_SERVER["HTTP_USER_AGENT"], "Opera Mini") !== false || strpos($_SERVER["HTTP_USER_AGENT"], "Opera Mobi") !== false) {
			$is_mobile = true;
		} else {
			$is_mobile = false;
		}
	}
	return $is_mobile;
}
?>