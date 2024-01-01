<!DOCTYPE html>
<?php
error_reporting(0);
include ("../common.php");
$tit=unfix_t(urldecode($_GET["title"]));
$image=$_GET["image"];
$link=urldecode($_GET["link"]);
$tip=$_GET["tip"];
$year=$_GET['year'];
/* ======================================= */
$width="200px";
$height="100px";
$fs_target="filme_link.php";
$has_img="no";
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="../custom.css" />
<title><?php echo $tit; ?></title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>

</head>
<body>
<?php

function str_between($string, $start, $end){
	$string = " ".$string; $ini = strpos($string,$start);
	if ($ini == 0) return ""; $ini += strlen($start); $len = strpos($string,$end,$ini) - $ini;
	return substr($string,$ini,$len);
}
echo '<h2>'.$tit.'</h2>';
$ua = "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:120.0) Gecko/20100101 Firefox/120.0";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $link);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $ua);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
  curl_setopt($ch,CURLOPT_REFERER,"https://fshd.ro/");
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $h = curl_exec($ch);
  curl_close($ch);

$n=0;
$videos = explode('<div class="se-q"', $h);
$sezoane=array();
unset($videos[0]);
$videos = array_values($videos);

foreach($videos as $video) {
  $t1=explode('class="title">',$video);
  $t2=explode('<',$t1[1]);
  if (preg_match("/sezonul\s+(\d+)/i",$t2[0],$m))
  $sezoane[]=trim($m[1]);
}
echo '<table border="1" width="100%">'."\n\r";

$p=0;
$c=count($sezoane);
for ($k=0;$k<$c;$k++) {
if ($p==0) echo '<TR>';
echo '<td class="sez" style="color:black;text-align:center"><a href="#sez'.($sezoane[$k]).'">Sezonul '.($sezoane[$k]).'</a></TD>';
$p++;
if ($p == 10) {
 echo '</tr>';
 $p=0;
 }
}
if ($p < 10 && $p > 0 && $k > 9) {
 for ($x=0;$x<10-$p;$x++) {
   echo '<TD></TD>'."\r\n";
 }
 echo '</TR>'."\r\n";
}
echo '</TABLE>';

foreach($videos as $video) {
  $t1=explode('class="title">',$video);
  $t2=explode('<',$t1[1]);
  if (preg_match("/sezonul\s+(\d+)/i",$t2[0],$m))
  $season=trim($m[1]);
  $sez = $season;
  echo '<table border="1" width="100%">'."\n\r";
  echo '<TR><td class="sez" style="color:black;background-color:#0a6996;color:#64c8ff;text-align:center" colspan="3">Sezonul '.($sez).'</TD></TR>';
  $n=0;

  $vids = explode('<li>', $video);
  unset($vids[0]);
  $vids = array_values($vids);
  foreach($vids as $vid) {
  $img_ep="";
  $episod="";
  $ep_tit="";
  $t1=explode('href="',$vid);
  $t2=explode('"',$t1[1]);
  $link=$t2[0];
  //Sezonul 1 Episodul 1
  preg_match("/sezonul\s+\d+\s+episodul\s+(\d+)/i",$vid,$m);
  $episod=$m[1];
  $img_ep="";
  $title="";
  $t1=explode('title="',$vid);
  $t2=explode('"',$t1[1]);
  $title=$t2[0];
  $title=str_replace("&nbsp;"," ",$title);
  $ep_tit=prep_tit($title);
  if ($ep_tit)
   $ep_tit_d=$season."x".$episod." ".$ep_tit;
  else
   $ep_tit_d=$season."x".$episod;
  $tit_link = $tit." ".$ep_tit_d;
  if ($episod) {
  $link_f=$fs_target.'?file='.urlencode($link).'&title='.urlencode(fix_t($tit_link));
   if ($n == 0) echo "<TR>"."\n\r";
   if ($has_img == "yes")
    echo '<TD class="mp" width="33%">'.'<a id="sez'.$sez.'" href="'.$link_f.'" target="_blank"><img width="'.$width.'" height="'.$height.'" src="'.$img_ep.'"><BR>'.$ep_tit_d.'</a></TD>'."\r\n";
   else
    echo '<TD class="mp" width="33%">'.'<a id="sez'.$sez.'" href="'.$link_f.'" target="_blank">'.$ep_tit_d.'</a></TD>'."\r\n";
   $n++;
   if ($n == 3) {
    echo '</TR>'."\n\r";
    $n=0;
   }
   }
}  
  if ($n < 3 && $n > 0) {
    for ($k=0;$k<3-$n;$k++) {
      echo '<TD></TD>'."\r\n";
    }
    echo '</TR>'."\r\n";
  }
echo '</table>';
}
?>
</body>
</html>
