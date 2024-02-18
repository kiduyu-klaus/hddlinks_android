<!doctype html>
<?php
//error_reporting(0);
include ("../common.php");
$list = glob($base_cookie."*.mcloud");
   foreach ($list as $l) {
    str_replace(" ","%20",$l);
    unlink($l);
}
require_once("bunny1.php");
$bunny=new bunny();
//hlPeNwkncH0fq9so
if (file_exists($base_pass."debug.txt"))
 $debug=true;
else
 $debug=false;

$list = glob($base_sub."*.srt");
   foreach ($list as $l) {
    str_replace(" ","%20",$l);
    unlink($l);
}
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
  s=document.getElementById('server').innerHTML;
  if (s.match(/vidstream|mycloud/gi)) {
  msg="mcloud1.php?id=" + encodeURI(link1) + "&title=" + link + "&tip=flash";
  window.open(msg);
  } else {
  msg="link1.php?file=" + link1 + "&title=" + link;
  window.open(msg);
  }
}
function openlink(link) {
  link1=document.getElementById('file').value;
  s=document.getElementById('server').innerHTML;
  if (s.match(/vidstream|mycloud/gi)) {
  msg="mcloud1.php?id=" + encodeURI(link1) + "&title=" + link + "&tip=mp";
  window.open(msg);
  } else {
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
$r=array();
$s=array();
//echo $link;
//die();
$info="";
if ($tip=="movie") {
$last_good="https://".parse_url($link)['host'];
$id = substr(strrchr($link, "-"), 1);
//echo $link;
$head=array('User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/109.0',
'Accept: application/json, text/javascript, */*; q=0.01',
'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
'Accept-Encoding: deflate',
'Referer: '.$link,
'X-Requested-With: XMLHttpRequest',
'Connection: keep-alive');
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $link);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
  curl_setopt($ch, CURLOPT_ENCODING, "");
  ///curl_setopt($ch, CURLOPT_HEADER,1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  $h = curl_exec($ch);
  //echo $h;
  $r=json_decode($h,1);
  $h=$r["result"];
  //echo $h;
  if (preg_match("/id\=\"film\-detail\"\>/",$h)) {
  $t1=explode('id="film-detail">',$h);
  $t2=explode('<div>Tags',$t1[1]);
  $info= $t2[0];
  $info=strip_tags($info);
  } elseif (preg_match("/id\=\"w\-info\"\>/",$h)) {
  $t1=explode('id="w-info">',$h);
  $t2=explode('<div>Tags',$t1[1]);
  $info= $t2[0];
  $info=strip_tags($info);
  }
  $t1=explode('data-id="',$h);
  $t2=explode('"',$t1[1]);
  $id=$t2[0];
  $vrf=$bunny->encodeVrf($id);
//echo $vrf."\n";
//die();
//$l=$last_good."/ajax/film/servers?id=".$id."&vrf=".$vrf."&token=";
//$l=$last_good."/ajax/server/list/".$id."?vrf=".$vrf;
  $l=$last_good."/ajax/episode/list/".$id."?vrf=".$vrf;
  curl_setopt($ch, CURLOPT_URL, $l);
  $h = curl_exec($ch);
  //echo $h;
  $r=json_decode($h,1);
  $h=$r["result"];
  $t1=explode('data-id="',$h);
  $t2=explode('"',$t1[1]);
  $id1=$t2[0];
  $vrf=$bunny->encodeVrf($id1);
  $l=$last_good."/ajax/server/list/".$id1."?vrf=".$vrf;
  curl_setopt($ch, CURLOPT_URL, $l);
  $h = curl_exec($ch);
  $r=json_decode($h,1);
  $h=$r["result"];


  curl_close($ch);

  //echo $h;
  //<li class="server
$r=array();
if (preg_match("/<div class\=\"film\-server\"/",$h))
$videos = explode('<div class="film-server"', $h);
else
$videos=explode('<li class="server',$h);
unset($videos[0]);
$videos = array_values($videos);
foreach($videos as $video) {
 $t1=explode('data-link-id="',$video);
 $t2=explode('"',$t1[1]);
 $t3=explode('data-id="',$video);
 $t4=explode('"',$t3[1]);
 $r[$t4[0]]=$t2[0];
}
} else {
  $t1=explode("&",$link);
  $link=$t1[0];
  $last_good=$t1[1];
  $vrf=$bunny->encodeVrf($link);
  $l=$last_good."/ajax/server/list/".$link."?vrf=".$vrf;
  //echo $l;
$head=array('User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/109.0',
'Accept: application/json, text/javascript, */*; q=0.01',
'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
'Accept-Encoding: deflate',
'Referer: '.$last_good,
'X-Requested-With: XMLHttpRequest',
'Connection: keep-alive');
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
  curl_setopt($ch, CURLOPT_ENCODING, "");
  ///curl_setopt($ch, CURLOPT_HEADER,1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  $h = curl_exec($ch);
  curl_close($ch);
  //echo $h;
  $r=json_decode($h,1);
  $h=$r["result"];
$r=array();
if (preg_match("/<div class\=\"film\-server\"/",$h))
$videos = explode('<div class="film-server"', $h);
else
$videos=explode('<li class="server',$h);
unset($videos[0]);
$videos = array_values($videos);
foreach($videos as $video) {
 $t1=explode('data-link-id="',$video);
 $t2=explode('"',$t1[1]);
 $t3=explode('data-id="',$video);
 $t4=explode('"',$t3[1]);
 $r[$t4[0]]=$t2[0];
}

}
$s=array("41"=>"Vidstream","28"=>"MyCloud","45"=>"Filemoon","40"=>"Streamtape");
//print_r ($r);
//print_r ($s);
//echo $s[key($r)];
$mcloud="";
$lang="";
foreach ($r as $kk => $v) {
  $vrf=$bunny->encodeVrf($v);
  $c_link=$last_good."/ajax/server/".$v."?vrf=".$vrf;
  $openload=$s[$kk];
  if (preg_match("/vidstream|mycloud/i",$openload)) {
  $head=array('User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/109.0',
  'Accept: application/json, text/javascript, */*; q=0.01',
  'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
  'Accept-Encoding: deflate',
  'Referer: '.$c_link,
  'X-Requested-With: XMLHttpRequest',
  'Connection: keep-alive');

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $c_link);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
  curl_setopt($ch, CURLOPT_ENCODING, "");
  //curl_setopt($ch, CURLOPT_HEADER,1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  curl_close($ch);
  //echo $h;
  $url=json_decode($h,1)['result']['url'];
  //echo $url."\n";
  $mcloud=$bunny->decodeVrf($url);
  //echo $mcloud;
  //$r[$kk]=$mcloud;

  $r[$kk]=$last_good."/ajax/server/".$v."?vrf=".urlencode($vrf);
  } else {
  $r[$kk]=$last_good."/ajax/server/".$v."?vrf=".urlencode($vrf);
  }
}
reset($r);
//////////////////////////
  $srt="";
  if (preg_match("/\?sub\.info\=/",$mcloud)) {
   $t1=explode("?sub.info=",$mcloud);
   $l1=urldecode($t1[1]);
   $t1=explode("&",$l1);
   //echo $l1;
  //https://bflix.to/ajax/episode/subtitles/11951
  $host1="https://bflix.to";
  $head=array('User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/116.0',
  'Accept: application/json, text/javascript, */*; q=0.01',
  'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
  'Accept-Encoding: deflate',
  'Origin: '.$host1,
  'Connection: keep-alive',
  'Referer: '.$host1.'/');
  //print_r ($head);
  // urldecode($t1[1])
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,urldecode($t1[0]));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
  curl_setopt($ch, CURLOPT_ENCODING, "");
  //curl_setopt($ch, CURLOPT_HEADER,1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  curl_close($ch);
  //echo $h;
  $ss=json_decode($h,1);
  //print_r ($ss);
  for ($k=0;$k<count($ss);$k++) {
   if (preg_match("/romanian/i",$ss[$k]['label'])) {
    $lang="Romanian";
    break;
   }
  }
  if (!$lang) {
  for ($k=0;$k<count($ss);$k++) {
   if (preg_match("/english/i",$ss[$k]['label'])) {
    $lang="English";
    break;
   }
  }
  }
  }


/////////////////////////
echo '<table border="1" width="100%">';
echo '<TR><TD class="mp">Alegeti un server: Server curent:<label id="server">'.$s[key($r)].'</label>
<input type="hidden" id="file" value="'."".urlencode($r[key($r)]).'"></td></TR></TABLE>';
echo '<table border="1" width="100%"><TR>';
$k=count($r);
$x=0;
foreach ($r as $kk => $v) {
  if ($x==0) echo '<TR>';
  $c_link=$v;
  $openload=$s[$kk];

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
  $imdbid="";
  $from="";
  $link_page="";
} else {
  $tit3=$tit;
  $sez=$sez;
  $ep=$ep;
  $imdbid="";
  $from="";
  $link_page="";
}
  $rest = substr($tit3, -6);
  if (preg_match("/\((\d+)\)/",$rest,$m)) {
   $year=$m[1];
   $tit3=trim(str_replace($m[0],"",$tit3));
  } else {
   $year="";
  }
$sub_link ="from=".$from."&tip=".$tip."&sez=".$sez."&ep=".$ep."&imdb=".$imdbid."&title=".urlencode(fix_t($tit3))."&link=".$link_page."&ep_tit=".urlencode(fix_t($tit2))."&year=".$year;
include ("subs.php");
echo '<table border="1" width="100%"><TR>';
if ($tip=="movie")
  $openlink=urlencode(fix_t($tit3));
else
  $openlink=urlencode(fix_t($tit.$tit2));
 if ($flash == "flash")
   echo '<TD align="center" colspan="4"><a id="viz" onclick="'."openlink1('".$openlink."')".'"'." style='cursor:pointer;'>".'VIZIONEAZA !</a></td>';
 else
   echo '<TD align="center" colspan="4"><a id="viz" onclick="'."openlink('".$openlink."')".'"'." style='cursor:pointer;'>".'VIZIONEAZA !</a></td>';
echo '</tr>';
echo '</table>';
//////////////////////////
echo '<br>';
if ($lang) {
 echo '<b>Subtitles: '.$lang."</b><BR>";
}
///////////////////////////
echo '<br>
<table border="0px" width="100%">
<TR>
<TD><font size="4"><b>Scurtaturi: 1=opensubtitles, 2=titrari, 3=subs, 4=subtitrari, 5=vizioneaza
<BR>Scurtaturi: 7=opensubtitles, 8=titrari, 9=subs, 0=subtitrari (cauta imdb id)
</b></font></TD></TR></TABLE>
';
echo '<BR>'.$info;
include("../debug.html");
echo '
<div id="overlay">
  <div id="text">Wait....</div>
</div>
</body>
</html>';
