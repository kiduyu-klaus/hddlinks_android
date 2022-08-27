<!doctype html>
<?php
include ("../common.php");
function decode_code($code){
    return preg_replace_callback(
        "@\\\(x)?([0-9a-fA-Z]{2,3})@",
        function($m){
            return mb_convert_encoding(chr($m[1]?hexdec($m[2]):octdec($m[2])),'ISO-8859-1', 'UTF-8');
        },
        $code
    );
}
$r=array();
function resolve2E($filelink) {
  global $r;
  $t1=explode("?",$filelink);
  $host=parse_url($t1[0])['host'];
  $ua = $_SERVER['HTTP_USER_AGENT'];
  require_once ("rec.php");
  $ch = curl_init($filelink);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  $h = curl_exec($ch);
  curl_close($ch);
  //echo $h;
  $t1=explode('data-recaptcha-key="',$h);
  $t2=explode('"',$t1[1]);
  $key=$t2[0];
  $t1=explode('data-id="',$h);  // only first
  $t2=explode('"',$t1[1]);
  $id=$t2[0];
  preg_match_all("/data-id=\"(\d+)\"/",$h,$m);
  for ($z=0;$z<count($m[0]);$z++) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  //$key="6LdBfTkbAAAAAL25IFRzcJzGj9Q-DKcrQCbVX__t";
  //$key="6Lf2aYsgAAAAAFvU3-ybajmezOYy87U4fcEpWS4C"; // 24.06.2022
  $co="aHR0cHM6Ly93d3cuMmVtYmVkLnJ1OjQ0Mw..";
  $co="aHR0cHM6Ly93d3cuMmVtYmVkLnJ1OjQ0Mw..";
  $loc="https://".$host;
  $sa="get_link";
  $id=$m[1][$z];
  $token=rec($key,$co,$sa,$loc);
  $l="https://".$host."/ajax/embed/play?id=".$id."&_token=".$token;
  $head=array('Accept: */*',
  'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
  'Accept-Encoding: deflate',
  'X-Requested-With: XMLHttpRequest',
  'Connection: keep-alive',
  'Referer: https://'.$host.'/embed/imdb/tv?id=tt9737326&s=1&e=3');

  curl_setopt($ch, CURLOPT_URL, $l);

  $h = curl_exec($ch);
  curl_close($ch);
  $x=json_decode($h,1);
  //print_r ($x);
  $r[]=$x['link'];
  }
}
error_reporting(0);
$list = glob($base_sub."*.srt");
   foreach ($list as $l) {
    str_replace(" ","%20",$l);
    unlink($l);
}
if (file_exists($base_pass."debug.txt"))
 $debug=true;
else
 $debug=false;
if (file_exists($base_pass."player.txt")) {
$flash=trim(file_get_contents($base_pass."player.txt"));
} else {
$flash="direct";
}
if (file_exists($base_pass."mx.txt")) {
$mx=trim(file_get_contents($base_pass."mx.txt"));
} else {
$mx="ad";
}
$user_agent     =   $_SERVER['HTTP_USER_AGENT'];
if ($flash != "mp") {
if (preg_match("/android|ipad/i",$user_agent) && preg_match("/chrome|firefox|mobile/i",$user_agent)) $flash="chrome";
}
$tit=unfix_t(urldecode($_GET["title"]));
$tit=prep_tit($tit);
$image=$_GET["image"];
$link=urldecode($_GET["link"]);
$tip=$_GET["tip"];
$sez=$_GET["sez"];
$ep=$_GET["ep"];
$ep_title=unfix_t(urldecode($_GET["ep_tit"]));
$ep_title=prep_tit($ep_title);
$year=$_GET["year"];
if ($tip=="movie") {
$tit2="";
} else {
if ($ep_title)
   $tit2=" - ".$sez."x".$ep." ".$ep_title;
else
   $tit2=" - ".$sez."x".$ep;
$tip="series";
}
$imdbid="";

function str_between($string, $start, $end){
	$string = " ".$string; $ini = strpos($string,$start);
	if ($ini == 0) return ""; $ini += strlen($start); $len = strpos($string,$end,$ini) - $ini;
	return substr($string,$ini,$len);
}
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title><?php echo $tit.$tit2; ?></title>
<link rel="stylesheet" type="text/css" href="../custom.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
<script type="text/javascript">
function openlink1(link) {
  link1=document.getElementById('file').value;
  msg="link1.php?file=" + link1 + "&title=" + link;
  window.open(msg);
}
function openlink(link) {
  on();
  var request =  new XMLHttpRequest();
  link1=document.getElementById('file').value;
  var the_data = "link=" + link1 + "&title=" + link;
  var php_file="link1.php";
  request.open("POST", php_file, true);			// set the request

  // adds a header to tell the PHP script to recognize the data as is sent via POST
  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  request.send(the_data);		// calls the send() method with datas as parameter

  // Check request status
  // If the response is received completely, will be transferred to the HTML tag with tagID
  request.onreadystatechange = function() {
    if (request.readyState == 4) {
      off();
      <?php
      if ($debug) echo "document.getElementById('debug').innerHTML = request.responseText.match(/http.+#/g);"."\r\n";
      ?>
      document.getElementById("mytest1").href=request.responseText;
      document.getElementById("mytest1").click();
    }
  }
}
function changeserver(s,t) {
  document.getElementById('server').innerHTML = s;
  document.getElementById('file').value=t;
}
   function zx(e){
     var charCode = (typeof e.which == "number") ? e.which : e.keyCode
     //alert (charCode);
     if (charCode == "49") {
      document.getElementById("opensub").click();
     } else if (charCode == "50") {
      document.getElementById("titrari").click();
     } else if (charCode == "51") {
      document.getElementById("subs").click();
     } else if (charCode == "52") {
      document.getElementById("subtitrari").click();
     } else if (charCode == "53") {
      document.getElementById("viz").click();
     } else if (charCode == "55") {
      document.getElementById("opensub1").click();
     } else if (charCode == "56") {
      document.getElementById("titrari1").click();
     } else if (charCode == "57") {
      document.getElementById("subs1").click();
     } else if (charCode == "48") {
      document.getElementById("subtitrari1").click();
     }
   }
document.onkeypress =  zx;
</script>
<script>
function on() {
    document.getElementById("overlay").style.display = "block";
}

function off() {
    document.getElementById("overlay").style.display = "none";
}
</script>
</head>
<body>
<a href='' id='mytest1'></a>
<?php
echo '<h2>'.$tit.$tit2.'</H2>';
echo '<BR>';
//echo $link;
//$link="https://europixhd.io/svop/srv2?search=dwm-angel-s01e04";
//$link=$link."-s2";
//echo $link;
$host=parse_url($link)['host'];
$r=array();
$ua = $_SERVER['HTTP_USER_AGENT'];
//$ua="Mozilla/5.0 (Windows NT 10.0; rv:71.0) Gecko/20100101 Firefox/71.0";
$cookie=$base_cookie."hdpopcorns.dat";

///////////////////////////////////////////////////////////////////////////////////
if ($tip=="series") {
  $ch = curl_init($link);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_REFERER, "https://123europix.pro");
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL
  curl_setopt($ch, CURLOPT_ENCODING,"");
  curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  curl_close ($ch);
$h=urldecode($h);
$h= preg_replace('/\\\x([a-f0-9]+)/msi',"chr(0x\\1)",$h);
$h=str_replace("\n","",$h);

if (preg_match("/var\s+_0x[a-z0-9A-Z]+\s*\=\s*\[((\".*?\"\,?)+)\]/ms", $h, $m)) {
$e="\$c0=array(".$m[1].");";
eval ($e);
//print_r ($c0);
if ($c0[0])
  $ep_index=$ep-1;
else
  $ep_index=$ep;
$link1 = $c0[$ep_index];
//echo $link1;
if (strpos($link1,"http") === false) {
  $link1="https://".$host.str_replace("../../","/",$link1);
}
}
} else {
  $link1=$link;
}
//echo $link1;
//$link1="https://europixhd.io/mov/christmas-reservations-2019-online-free-hd-with-subtitles-europix";
if (strpos($link1,"europix") !== false && strpos($link1,"http") !== false) {
$path=parse_url($link1)['path'];
//echo $path;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $link1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, $ua);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
curl_setopt($ch, CURLOPT_REFERER, "https://123europix.pro");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
$h = curl_exec($ch);
curl_close($ch);
$h=urldecode($h);
$h=decode_code($h);
//$h=str_replace("\n","",$h);
//echo $h;
if (preg_match("/title\/tt(\d+)/",$h,$y))
  $imdbid=$y[1];
else
  $imdbid="";
if (preg_match("/(newsrv\S+)(\'|\")/",$h)) {
if ($tip=="movie")      //newsrv3?search=dwm-servant-s01e01-
  preg_match_all("/(\/svop\S+)(\'|\")/",$h,$p);
else
  preg_match_all("/(newsrv\S+)(\'|\")/",$h,$p);
//print_r ($p);
for ($k=0;$k<count($p[1]);$k++) {

  if ($tip=="movie")
  $l="https://".$host."".$p[1][$k];
  else
  $l="https://".$host."/svop/".$p[1][$k];
  //$l="https://europixhd.io/svop2/zznewsrv2?search=dwm-christmas-reservations-2019";
  //$l="https://europixhd.io/svop2/newsrv1?search=dwm-christmas-reservations-2019";
  //echo $l."================="."\n";;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
  curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
  curl_setopt($ch, CURLOPT_REFERER, "https://123europix.pro");
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_HEADER,1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  curl_close($ch);
  $h=urldecode($h);
  //echo $h;
  if (preg_match("/((iframe\s*src)|(window\.location))\=(\'|\")(.*?)(\'|\")/",$h,$m)) {
  $link1=$m[5];
  //print_r ($m);
  if(strpos($link1,"http") !== false) $r[] = $link1;
  }
}
} else {
  if (preg_match("/((iframe\s*src)|(window\.location))\=(\'|\")(.*?)(\'|\")/",$h,$m)) {
  $link1=$m[5];
  //print_r ($m);
  if(strpos($link1,"http") !== false) $r[] = $link1;
  }
}
} else {
if(strpos($link1,"http") !== false) $r[] = $link1;
}
//}
for ($c=0;$c<count($r);$c++) {
  if (preg_match("/watch\-series\.site/",$r[$c])) unset($r[$c]);
  if (preg_match("/2embed\./",$r[$c])) {
    resolve2E($r[$c]);
    //unset($r[$c]);
  }
  if (preg_match("/vidnext\.net|vidnode\.net|vidembed\.(net|cc|io)|\/vidcloud9\.|membed\.net/",$r[$c])) {
   $head=array('Accept: application/json, text/javascript, */*; q=0.01',
   'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
   'Accept-Encoding: deflate',
   'X-Requested-With: XMLHttpRequest',
   'Connection: keep-alive');

   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $r[$c]);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; rv:71.0) Gecko/20100101 Firefox/71.0');
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
   curl_setopt($ch, CURLOPT_TIMEOUT, 25);
   $h2 = curl_exec($ch);
   curl_close($ch);
   //echo $h2;
   preg_match_all("/data\-video\=\"([^\"]+)/",$h2,$m);
   for ($z=0;$z<count($m[1]);$z++) {
     $r[]=$m[1][$z];
   }
  }
}
sort($r);
//print_r ($r);
echo '<table border="1" width="100%">';
echo '<TR><TD class="mp">Alegeti un server: Server curent:<label id="server">'.parse_url($r[0])['host'].'</label>
<input type="hidden" id="file" value="'.urlencode($r[0]).'"></td></TR></TABLE>';
echo '<table border="1" width="100%"><TR>';
$k=count($r);
$x=0;
for ($i=0;$i<$k;$i++) {
  if ($x==0) echo '<TR>';
  $c_link=$r[$i];
  $openload=parse_url($r[$i])['host'];
  if (preg_match($indirect,$openload)) {
  echo '<TD class="mp"><a href="filme_link.php?file='.urlencode($c_link).'&title='.urlencode(unfix_t($tit.$tit2)).'" target="_blank">'.$openload.'</a></td>';
  } else
  echo '<TD class="mp"><a id="myLink" href="#" onclick="changeserver('."'".$openload."','".urlencode($c_link)."'".');return false;">'.$openload.'</a></td>';
  $x++;
  if ($x==6) {
    echo '</TR>';
    $x=0;
  }
}
if ($x < 6 && $x > 0 & $k>6) {
 for ($k=0;$k<6-$x;$k++) {
   echo '<TD></TD>'."\r\n";
 }
 echo '</TR>'."\r\n";
}
echo '</TABLE>';
if ($tip=="movie") {
  $tit3=$tit;
  $tit2="";
  $sez="";
  $ep="";
  //$imdbid="";
  $from="";
  $link_page="";
} else {
  $tit3=$tit;
  $sez=$sez;
  $ep=$ep;
  //$imdbid="";
  $from="";
  $link_page="";
}
$sub_link ="from=".$from."&tip=".$tip."&sez=".$sez."&ep=".$ep."&imdb=".$imdbid."&title=".urlencode(fix_t($tit3))."&link=".$link_page."&ep_tit=".urlencode(fix_t($tit2))."&year=".$year;
include ("subs.php");
echo '<table border="1" width="100%"><TR>';
if ($tip=="movie")
  $openlink=urlencode(fix_t($tit3));
else
  $openlink=urlencode(fix_t($tit.$tit2));
 if ($flash != "mp")
   echo '<TD align="center" colspan="4"><a id="viz" onclick="'."openlink1('".$openlink."')".'"'." style='cursor:pointer;'>".'VIZIONEAZA !</a></td>';
 else
   echo '<TD align="center" colspan="4"><a id="viz" onclick="'."openlink('".$openlink."')".'"'." style='cursor:pointer;'>".'VIZIONEAZA !</a></td>';
echo '</tr>';
echo '</table>';
echo '<br>
<table border="0px" width="100%">
<TR>
<TD><font size="4"><b>Scurtaturi: 1=opensubtitles, 2=titrari, 3=subs, 4=subtitrari, 5=vizioneaza
<BR>Scurtaturi: 7=opensubtitles, 8=titrari, 9=subs, 0=subtitrari (cauta imdb id)
</b></font></TD></TR></TABLE>
';
include("../debug.html");
echo '
<div id="overlay">
  <div id="text">Wait....</div>
</div>
</body>
</html>';
