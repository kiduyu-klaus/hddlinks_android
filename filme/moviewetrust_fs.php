<!doctype html>
<?php
include ("../common.php");
//error_reporting(0);
$list = glob($base_sub."*.srt");
   foreach ($list as $l) {
    str_replace(" ","%20",$l);
    unlink($l);
}
if (file_exists("vidsrc.txt")) unlink ("vidsrc.txt");
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
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript">
function openlink1(link) {
  link1=document.getElementById('file').value;
  //alert (link1);
  if (link1.match(/streamembed/gi)) {
  msg="streamembed1.php?file=" + link1 + "&title=" + link + "&tip=flash";
  window.open(msg);
  } else {
  msg="link1.php?file=" + link1 + "&title=" + link;
  window.open(msg);
  }
}
function openlink(link) {
  link1=document.getElementById('file').value;
  if (link1.match(/streamembed/gi)) {
  msg="streamembed1.php?file=" + link1 + "&title=" + link + "&tip=mp";
  window.open(msg);
  } else {
  on();
  var request =  new XMLHttpRequest();
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
if ($flash=="flash1") {
echo '
<script>
        setInterval(function(){
            $.get("vidsrc_pass.php", function(data, status){
                //console.log(data);
                //alert (data);
            });
        }, 60000);
</script>
';
}
echo '<h2>'.$tit.$tit2.'</H2>';
echo '<BR>';

$r=array();
$s=array();
$ua="Mozilla/5.0 (Windows NT 10.0; rv:88.0) Gecko/20100101 Firefox/88.0";
if (file_exists($base_pass."tmdb.txt"))
  $api_key=file_get_contents($base_pass."tmdb.txt");
else
  $api_key="";
///////////////////////////////////////////////////////
if ($tip=="movie")
$l="https://api.themoviedb.org/3/movie/".$link."?api_key=".$api_key."&append_to_response=external_ids";
else
$l="https://api.themoviedb.org/3/tv/".$link."?api_key=".$api_key."&append_to_response=external_ids";
  $ch = curl_init($l);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  $html = curl_exec($ch);
  curl_close ($ch);
  $x=json_decode($html,1);
  //print_r ($x);
  //die();
  $imdb=$x['external_ids']['imdb_id'];
  //echo $imdb;
  //die();
if ($tip=="movie")
$l="https://fsapi.xyz/tmdb-movie/".$link;
else
$l="https://fsapi.xyz/tv-tmdb/".$link."-".$sez."-".$ep;
//echo $l;
//die();
  $head=array('Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
  'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
  'Accept-Encoding: deflate',
  'Connection: keep-alive');
  /*
  $ch = curl_init($l);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
  curl_setopt($ch, CURLOPT_ENCODING,"");
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  curl_close ($ch);
  //echo $h;
  preg_match_all("/url\=([a-zA-Z0-9\/\+\=]+)\"/si",$h,$m);
  //print_r ($m);
  for ($k=0;$k<count($m[1]);$k++) {
   $z[]=base64_decode($m[1][$k]);
   $l=base64_decode($m[1][$k]);
   //echo $l."\n";
   if (preg_match("/vidnext\.net|membed\./",$l)) {
     if ($l[0] =="/") $l="https:".$l;
     $r[]=$l;
     $s[]="vidnext";
   }
  }
  */
  //print_r ($r);
//die();
//////////////////////////////////////////////////////////////////
//olgply.xyz
/*
if ($tip == "movie")
  $l="https://olgply.xyz/".$link;
else
  $l="https://olgply.xyz/".$link."/".$sez."/".$ep;
//echo $l;
//die();
//$l="https://olgply.com/".$link;
  $s[]="olgply";
  $r[]=$l;
*/

//////////////////////////////////////////////////////////////////
// apimdb.net   now openvids.io
function get_link($o,$x) {
if($o == "youtube") return "https://www.youtube.com/embed/".$x;
if($o == "doodstream") return "https://dood.pm/e/".$x;
if($o == "voe") return "https://voe.sx/e/".$x;
if($o == "streamsb") return "https://sbembed.com/e/".$x;
if($o == "mixdrop") return "https://mixdrop.co/e/".$x;
if($o == "voxzer") return "https://player.voxzer.org/view/".$x;
if($o == "vidcloud") return "https://membed.net/streaming.php?id=".$x;
}
$ua="Mozilla/5.0 (Windows NT 10.0; rv:75.0) Gecko/20100101 Firefox/75.0";
$l="https://openvids.to/api/servers";
if ($tip=="movie") {
 $p=array(
   "type" => "movie",
   "_id" => $imdb,
   "film" => array(
     "_id" => $imdb,
     "title" => "",
     "overview" => "",
     "tmdbId" => "",
     "backdrop" => "",
     "releasedAt" => "2016-08-03T00:00:00.000Z",
     "updatedAt" => "2000-05-23T03:03:26.787Z"),
   );
} else {
 $p=array(
   "type" => "episode",
   "_id" => $imdb."-".$sez."-".$ep,
   "film" => array(
     "_id" => $imdb."-".$sez."-".$ep,
     "tvName" => "",
     "season" => $sez,
     "episode" => $ep,
     "title" => "",
     "overview" => "",
     "tmdbId" => "",
     "backdrop" => "",
     "releasedAt" => "2016-08-03T00:00:00.000Z",
     "episodeReleasedAt" => "2021-09-17T00:00:00.000Z",
     "updatedAt" => "2000-05-23T03:03:26.787Z"),
   );
}
$post=json_encode($p);
//echo $post;
//$post='{"type":"movie","_id":"tt0371746","film":{"_id":"tt0371746","title":"Iron Man","overview":"After being held captive in an Afghan cave, billionaire engineer Tony Stark creates a unique weaponized suit of armor to fight evil.","tmdbId":1726,"backdrop":"/cyecB7godJ6kNHGONFjUyVN9OX5.jpg","releasedAt":"2008-04-30T00:00:00.000Z","updatedAt":"2000-05-23T03:03:26.787Z"}}';
$head=array('User-Agent: Mozilla/5.0 (Windows NT 10.0; rv:106.0) Gecko/20100101 Firefox/106.0',
'Accept: */*',
'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
'Accept-Encoding: deflate',
'Referer: https://openvids.to/',
'Content-Type: application/json',
'Content-Length: '.strlen($post),
'Origin: https://openvids.to',
'Alt-Used: openvids.io',
'Connection: keep-alive',
'Sec-Fetch-Dest: empty',
'Sec-Fetch-Mode: cors',
'Sec-Fetch-Site: same-origin');
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  //curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_POST,1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
  //curl_setopt($ch, CURLOPT_REFERER,"https://openvids.io/movie/tt0371746");
  //curl_setopt($ch, CURLOPT_HEADER,1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 2);
  $h = curl_exec($ch);
  if (preg_match("/servers/",$h)) {
  $z=json_decode($h,1)['servers'];

  foreach ($z as $key => $value) {
    //echo $key;
    $s[]=$value['name'];
    $r[]="o. ".get_link($value['name'],$value['code']);
  }
  }
  //print_r ($r);
  //die();
////////////////////////////////////////////////////////////
// vidsrc.me
if ($tip=="movie")
  $l="https://vidsrc.me/embed/".$imdb."/";
else
  $l="https://vidsrc.me/embed/".$imdb."/".$sez."-".$ep."/";
  //echo $l;
  //$l= "https://v2.vidsrc.me/embed/tt1300854/";
  $ch = curl_init($l);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  //curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
  curl_setopt($ch, CURLOPT_ENCODING,"");
  curl_setopt($ch, CURLOPT_HEADER,1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  curl_close ($ch);
  if (preg_match("/location:\s+(.+)/i",$h,$m))
   $host=parse_url(trim($m[1]))['host'];
  else
   $host="v2.vidsrc.me";
  //echo $h;
  preg_match_all("/data\-hash\=\"([a-zA-Z0-9\/\+\=\-\_]+)\"/si",$h,$m);
   $head=array('Accept: */*',
    'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
    'Accept-Encoding: deflate',
    'X-Requested-With: XMLHttpRequest',
    'Connection: keep-alive',
    'Referer: https://'.$host.'/');
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_USERAGENT, $ua);
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,0);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
   curl_setopt($ch, CURLOPT_ENCODING,"");
   curl_setopt($ch, CURLOPT_HEADER,1);
   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
   curl_setopt($ch, CURLOPT_TIMEOUT, 15);
   if (preg_match("/player\_iframe\"\s+src\=\"([^\"]+)/",$h,$n)) {
   if (substr($n[1],0,4)=="http")
     $l=$n[1];
   elseif (substr($n[1],0,2)=="//")
     $l="https:".$n[1];
   $r[]=$l;
   $s[]="v. ".parse_url($l)['host'];
   }
   for ($k=0;$k<count($m[1]);$k++) {
   if (substr($m[1][$k],0,4)=="http")
     $l=$m[1][$k];
   elseif (substr($m[1][$k],0,2)=="//")
     $l="https:".$m[1][$k];
   else
    $l="https://".$host."/src/".$m[1][$k];
    //echo $l;
    curl_setopt($ch, CURLOPT_URL, $l);
    $h = curl_exec($ch);
    //echo $h;
    preg_match("/location\:\s+(.+)/i",$h,$m1);
    $r[]=trim($m1[1]);
    $s[]="v. ".parse_url(trim($m1[1]))['host'];
   }
   curl_close ($ch);
   //die();
   if ($tip=="movie")
     $r[]="https://voidboost.net/embed/".$imdb."?t=20&td=20&tlabel=English&cc=off&plang=en&poster=1";
   else
     $r[]="https://voidboost.net/embed/".$imdb."?&s=".$sez."&e=".$ep."&t=20&td=20&tlabel=English&cc=off&plang=en&poster=1";
   $s[]="voidboost";
   $imdbid=str_replace("tt","",$imdb);
   if ($tip=="movie")
     $l="https://seapi.link/?type=imdb&id=".$imdb."&max_results=1";
   else
     $l="https://seapi.link/?type=imdb&id=".$imdb."&season=".$sez."&episode=".$ep."&max_results=1";
   //echo $l;
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $l);
   curl_setopt($ch, CURLOPT_USERAGENT, $ua);
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   //curl_setopt($ch, CURLOPT_HTTPHEADER,$head);
   curl_setopt($ch, CURLOPT_ENCODING,"");
   //curl_setopt($ch, CURLOPT_HEADER,1);
   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
   curl_setopt($ch, CURLOPT_TIMEOUT, 15);
   $h = curl_exec($ch);
   curl_close ($ch);
   $x=json_decode($h,1);
   //print_r ($x);
   for ($k=0;$k<count($x['results']);$k++) {
     $y=$x['results'][$k]['url'];
     $r[]= $y;
     $s[]= "1. ".$x['results'][$k]['server'];
   }
   $test=$x['results'][0]['url'];
/// embedo.xyz
  if ($tip=="movie")
   $l="https://embedo.xyz/player.php?video_id=".$imdb;
  else
   $l="https://embedo.xyz/player.php?video_id=".$imdb."&s=".$sez."&e=".$ep;
  //echo $l;

////////////////////////////////////////////////////////////////////////
  if ($tip=="movie")
   $l="https://embedo.xyz/play/movie.php?imdb=".$imdb;
  else
   $l="https://embedo.xyz/play/series.php?imdb=".$imdb."&sea=".$sez."&epi=".$ep;
   //echo $l;
  $r[]=$l;
  $s[]="Vidcloud";
///////////////////////////////////////////
//print_r ($r);
echo '<table border="1" width="100%">';
echo '<TR><TD class="mp">Alegeti un server: Server curent:<label id="server">'.$s[0].'</label>
<input type="hidden" id="file" value="'.urlencode($r[0]).'"></td></TR></TABLE>';
echo '<table border="1" width="100%"><TR>';
$k=count($r);
$x=0;
for ($i=0;$i<$k;$i++) {
  if ($x==0) echo '<TR>';
  $c_link=$r[$i];
  $openload=$s[$i];
  if (preg_match("/streamembed1/",$c_link)) {
  echo '<TD class="mp"><a href="streamembed1.php?file='.urlencode($c_link).'&title='.urlencode(unfix_t($tit.$tit2)).'&tip='.$flash.'" target="_blank">'.$openload.'</a></td>';
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
$imdbid=str_replace("tt","",$imdb);
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
//echo '<a href="https://streamembed.net/play/YTF0TklLYXplRnRhdjNTcHBUQUxnUzd1amt0UkIrZTJTWUZlQk8wYXJsWXhlT2EzTkxaQkU0RU9HQ2ZwemhvPQ==">sasaasas</a>';
include("../debug.html");
echo '
<div id="overlay">
  <div id="text">Wait....</div>
</div>
</body>
</html>';
