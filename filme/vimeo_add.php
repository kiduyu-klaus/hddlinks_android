<?php
//error_reporting(0);
//62
include ("../common.php");
//$fav_link="mod=add&title=".urlencode(fix_t($title11))."&link=".$link1."&image=".$image;
$mod=$_POST["mod"];
$link=$_POST["link"];
$title=$_POST["title"];
$image=urldecode($_POST["image"]);
$file=$base_fav."vimeo.dat";
$arr=array();
$h="";
if (file_exists($file)) {
  $h=file_get_contents($file);
  $t1=explode("\r\n",$h);
  for ($k=0;$k<count($t1) -1;$k++) {
    $a=explode("#separator",$t1[$k]);
    if ($a) {
      $tit=trim($a[0]);
      $l=trim($a[1]);
      $img=trim($a[2]);
      //$arr[$tit]["link"]=$l;
      //$arr[$tit]["image"]=$img;
      $arr[$k]=array($tit,$l,$img);
    }
  }
}
if ($mod=="add") {
  $found=false;
  if ($arr) {
  $found=false;
  foreach($arr as $key => $value) {
    if ($title == $arr[$key][0] && $link == $arr[$key][1]) {
      $found=true;
      break;
    }
  }
  if (!$found) {
    //$arr[$title]["link"]=$link;
    //$arr[$title]["image"]=$image;
    $arr[]=array($title,$link,$image);
    echo "Am adaugat clipul ".unfix_t(urldecode($title));
  }
  asort($arr);
  } else {
    //$arr[$title]["link"]=$link;
    //$arr[$title]["image"]=$image;
    $arr[]=array($title,$link,$image);
    echo "Am adaugat clipul ".unfix_t(urldecode($title));
  }
  $out="";
  //print_r ($arr);
  asort ($arr);
  foreach($arr as $key => $value) {
    $out =$out.$arr[$key][0]."#separator".$arr[$key][1]."#separator".$arr[$key][2]."\r\n";
  }
  //echo $out;
  if ($found) echo "Clipul a fost adaugat deja!";
  file_put_contents($file,$out);
} else { // delete
  $found=false;
  //echo $title;
  if ($arr) {
  $found=false;
  foreach($arr as $key => $value) {
    if ($title == $arr[$key][0] && $link==$arr[$key][1]) {
      $found=true;
      //echo $title;
      unset ($arr[$key]);
      echo "Am sters clipul ".unfix_t(urldecode($title));
      break;
    }
  }
  if ($arr) {
    asort($arr);
    $out="";
    //print_r ($arr);
    foreach($arr as $key => $value) {
      $out =$out.$arr[$key][0]."#separator".$arr[$key][1]."#separator".$arr[$key][2]."\r\n";
    }
    file_put_contents($file,$out);
   }
 }
}
  
?>
