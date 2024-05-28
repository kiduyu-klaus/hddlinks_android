<!DOCTYPE html>
<?php
include ("../common.php");
$page_title="tvonline";
$width="200px";
$height=intval(200*(394/700))."px";
?>
<html><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
<meta http-equiv="Pragma" content="no-cache"/>
<meta http-equiv="Expires" content="0"/>
      <title><?php echo $page_title; ?></title>
<script type="text/javascript" src="//code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="../jquery.fancybox.min.js"></script>
<link rel="stylesheet" type="text/css" href="../jquery.fancybox.min.css">
<link rel="stylesheet" type="text/css" href="../custom.css" />
<script type="text/javascript">
var id_link="";
function ajaxrequest(link) {
  var request =  new XMLHttpRequest();
  on();
  var the_data = link;
  var php_file='direct_link.php';
  request.open('POST', php_file, true);			// set the request

  // adds a header to tell the PHP script to recognize the data as is sent via POST
  request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  request.send(the_data);		// calls the send() method with datas as parameter

  // Check request status
  // If the response is received completely, will be transferred to the HTML tag with tagID
  request.onreadystatechange = function() {
    if (request.readyState == 4) {
      off();
      document.getElementById("mytest1").href=request.responseText;
      document.getElementById("mytest1").click();
    }
  }
}

function isValid(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode,
    self = evt.target;
    if (charCode == "49") {
     id = "imdb_" + self.id;
     id_link=self.id;
     val_imdb=document.getElementById(id).value;
     msg="prog.php?" + val_imdb;
     document.getElementById("fancy").href=msg;
     document.getElementById("fancy").click();
    }
    return true;
}
   function zx(e){
     var instance = $.fancybox.getInstance();
     var charCode = (typeof e.which == "number") ? e.which : e.keyCode
     if (charCode == "13"  && instance !== false) {
       $.fancybox.close();
       setTimeout(function(){ document.getElementById(id_link).focus(); }, 500);
     }
   }
function isKeyPressed(event) {
  if (event.ctrlKey) {
    id = "imdb_" + event.target.id;
    //alert (id);
    val_imdb=document.getElementById(id).value;
    msg="prog.php?" + val_imdb;
    document.getElementById("fancy").href=msg;
    document.getElementById("fancy").click();
  }
}
function prog(link) {
    msg="prog.php?" + link;
    document.getElementById("fancy").href=msg;
    document.getElementById("fancy").click();
}
$(document).on('keyup', '.imdb', isValid);
document.onkeypress =  zx;
</script>

</head>
<body>
<script>
function on() {
    document.getElementById("overlay").style.display = "block";
}

function off() {
    document.getElementById("overlay").style.display = "none";
}
</script>
<a href='' id='mytest1'></a>
<a id="fancy" data-fancybox data-type="iframe" href=""></a>
<?php
function str_between($string, $start, $end){
	$string = " ".$string; $ini = strpos($string,$start);
	if ($ini == 0) return ""; $ini += strlen($start); $len = strpos($string,$end,$ini) - $ini;
	return substr($string,$ini,$len);
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

$n=0;
$w=0;
echo '<h2>'.$page_title.'</H2>';
echo '<table border="1px" width="100%">'."\n\r";
echo '<TR>';
echo '<TD class="mp" width="25%"><a href="#sport">Sport</a></TD>';
echo '<TD class="mp" width="25%"><a href="#filme">Filme</a></TD>';
echo '<TD class="mp" width="50%"><a href="#doc">Documentare</a></TD>';
echo '</TR></TABLE>';
$head=array('User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/118.0',
'Accept: */*',
'Accept-Language: ro-RO,ro;q=0.8,en-US;q=0.6,en-GB;q=0.4,en;q=0.2',
'Accept-Encoding: deflate',
'Origin: https://tvonline123.net',
'Connection: keep-alive',
'Referer: https://www.tvonline123.com/index.php?categoria=sport');
echo '<a id="sport"></a>';
$l2="https://tvonline123.net/categoria/sport.html";
$l2="https://www.tvonline123.com/categoria/sport.html";
  $cookie=$base_cookie."tvonline.txt";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $l2);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  //curl_setopt($ch, CURLOPT_POST,1);
  //curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
  curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
  curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
  curl_setopt($ch, CURLOPT_HEADER,1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
  curl_setopt($ch, CURLOPT_TIMEOUT, 25);
  $h = curl_exec($ch);
  //echo $h;
echo '<table border="1px" width="100%">'."\n\r";
$videos = explode('<div class="col-6 col-lg-2', $h);
unset($videos[0]);
$n=0;
$videos = array_values($videos);
  foreach($videos as $video) {
  $t1=explode('title="',$video);
  $t2=explode('"',$t1[1]);
  $title=$t2[0];
  $title=preg_replace("/\s*Online/i","",$title);
  $t1=explode('href="',$video);
  $t2=explode('"',$t1[1]);
  $link=$t2[0];
  $link=fixurl($link,$l2);
  $t1=explode('src="',$video);
  $t2=explode('"',$t1[1]);
  $image=$t2[0];
    $val_prog="link=".urlencode(fix_t($title));
    $link1="direct_link.php?link=".$link."&title=".urlencode($title)."&from=tvonline&mod=direct";
    $l="link=".urlencode(fix_t($link))."&title=".urlencode(fix_t($title))."&from=tvonline&mod=direct";
    $link1="tvonline_fs.php?link=".$link."&title=".urlencode($title);
  if ($link <> "") {
  if ($n==0) echo '<TR>';
  echo '<td class="mp" align="center" width="25%"><a class ="imdb" id="myLink'.($w*1).'" href="'.$link1.'" target="_blank" onmousedown="isKeyPressed(event)">
  <img id="myLink'.($w*1).'" src="'.$image.'" width="'.$width.'" height="'.$height.'"><BR>'.$title.
  '<input type="hidden" id="imdb_myLink'.($w*1).'" value="'.$val_prog.'"></a>
  </TD>';
  $w++;
  $n++;
  if ($n == 4) {
  echo '</tr>';
  $n=0;
  }
  }
}
echo "</table>";
echo '<a id="filme"></a>';
$l2="https://www.tvonline123.com/categoria/filme.html";
curl_setopt($ch, CURLOPT_URL, $l2);
$h = curl_exec($ch);

echo '<table border="1px" width="100%">'."\n\r";
$videos = explode('<div class="col-6 col-lg-2', $h);
unset($videos[0]);
$n=0;
$videos = array_values($videos);
  foreach($videos as $video) {
  $t1=explode('title="',$video);
  $t2=explode('"',$t1[1]);
  $title=$t2[0];
  $title=preg_replace("/\s*Online/i","",$title);
  $t1=explode('href="',$video);
  $t2=explode('"',$t1[1]);
  $link=$t2[0];
  $link=fixurl($link,$l2);
  $t1=explode('src="',$video);
  $t2=explode('"',$t1[1]);
  $image=$t2[0];
    $val_prog="link=".urlencode(fix_t($title));
    $link1="direct_link.php?link=".$link."&title=".urlencode($title)."&from=tvonline&mod=direct";
    $l="link=".urlencode(fix_t($link))."&title=".urlencode(fix_t($title))."&from=tvonline&mod=direct";
    $link1="tvonline_fs.php?link=".$link."&title=".urlencode($title);
  if ($link <> "") {
  if ($n==0) echo '<TR>';
  echo '<td class="mp" align="center" width="25%"><a class ="imdb" id="myLink'.($w*1).'" href="'.$link1.'" target="_blank" onmousedown="isKeyPressed(event)">
  <img id="myLink'.($w*1).'" src="'.$image.'" width="'.$width.'" height="'.$height.'"><BR>'.$title.
  '<input type="hidden" id="imdb_myLink'.($w*1).'" value="'.$val_prog.'"></a>
  </TD>';
  $w++;
  $n++;
  if ($n == 4) {
  echo '</tr>';
  $n=0;
  }
  }
}
echo "</table>";
echo '<a id="doc"></a>';
$l2="https://www.tvonline123.com/categoria/documentare.html";
curl_setopt($ch, CURLOPT_URL, $l2);
$h = curl_exec($ch);
echo '<table border="1px" width="100%">'."\n\r";
$videos = explode('<div class="col-6 col-lg-2', $h);
unset($videos[0]);
$n=0;
$videos = array_values($videos);
  foreach($videos as $video) {
  $t1=explode('title="',$video);
  $t2=explode('"',$t1[1]);
  $title=$t2[0];
  $title=preg_replace("/\s*Online/i","",$title);
  $t1=explode('href="',$video);
  $t2=explode('"',$t1[1]);
  $link=$t2[0];
  $link=fixurl($link,$l2);
  $t1=explode('src="',$video);
  $t2=explode('"',$t1[1]);
  $image=$t2[0];
    $val_prog="link=".urlencode(fix_t($title));
    $link1="direct_link.php?link=".$link."&title=".urlencode($title)."&from=tvonline&mod=direct";
    $l="link=".urlencode(fix_t($link))."&title=".urlencode(fix_t($title))."&from=tvonline&mod=direct";
    $link1="tvonline_fs.php?link=".$link."&title=".urlencode($title);
  if ($link <> "") {
  if ($n==0) echo '<TR>';
  echo '<td class="mp" align="center" width="25%"><a class ="imdb" id="myLink'.($w*1).'" href="'.$link1.'" target="_blank" onmousedown="isKeyPressed(event)">
  <img id="myLink'.($w*1).'" src="'.$image.'" width="'.$width.'" height="'.$height.'"><BR>'.$title.
  '<input type="hidden" id="imdb_myLink'.($w*1).'" value="'.$val_prog.'"></a>
  </TD>';
  $w++;
  $n++;
  if ($n == 4) {
  echo '</tr>';
  $n=0;
  }
  }
}
echo "</table>";
curl_close($ch);
?>
<div id="overlay"">
  <div id="text">Wait....</div>
</div>
</body>
</html>
