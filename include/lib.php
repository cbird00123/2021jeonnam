<?php 

$GLOBALS['db'] = mysqli_connect("localhost","root","","2021_jeonnam");

mysqli_query($GLOBALS['db'], "set names utf8mb4");

header('Content-type:text/html; charset=utf-8;');
session_start();
date_default_timezone_set("Asia/Seoul");
		
function sql($sql) {
	return mysqli_query($GLOBALS['db'], $sql);
}

function fe($sql) {
	return mysqli_fetch_array($sql);
}

function num($sql) {
	return mysqli_num_rows($sql);
}

function alert($msg){
	echo "<script>alert('{$msg}')</script>";
}

function move($msg){
	echo "<script>location.replace('{$msg}')</script>";
	exit();
}

function back(){
	echo "<script>history.back()</script>";
	exit();
}

function clog($a){
	echo "<script>console.log('{$a}')</script>";
}

function member_chk(){
	if(!isset($_SESSION['id'])){
		alert("접근 오류");
		back();
	}
}

function echoo($str){
	$txt = addslashes($str);
	echo $txt;
}

function get_fe($table,$tar,$val){
	$res = sql("select * from {$table} where {$tar} = '{$val}' order by id desc");
	return fe($res);
}

function get_date($date){
	$d = strtotime($date);
	return date("Y년 m월 d일 Ah:i",$d);
}

if($_POST){
	extract($_POST);
}

if(isset($_GET['q'])){
	$var = explode("/",$_GET['q']);
	$page_mode = isset($var[0]) ? $var[0] : "";
}else{
	$page_mode = "main";
}

$no = isset($var[1]) ? $var[1] : "";
$page = isset($var[2]) ? $var[2] : "";
$search = isset($var[3]) ? $var[3] : "";

$url = $_SERVER['REQUEST_URI'];

$sid = isset($_SESSION['id']) ? $_SESSION['id'] : "";
$sname = isset($_SESSION['name']) ? $_SESSION['name'] : "";
$stype = isset($_SESSION['type']) ? $_SESSION['type'] : "";
$sloca = isset($_SESSION['loca']) ? $_SESSION['loca'] : "";
$strans = isset($_SESSION['trans']) ? $_SESSION['trans'] : "";


$GLOBALS['dr'] = array();
$re2 = sql("select * from distances");
while($da2 = fe($re2)){
	$GLOBALS['dr'][$da2['vertex1']][$da2['vertex2']] = $da2['distance'];
}

function cal_dis($start,$end){
	$d_arr = $GLOBALS['dr'];
	$S = array();
	$Q = array();
	foreach(array_keys($d_arr) as $val) $Q[$val] = 99999;
	$Q[$start] = 0;

	while(!empty($Q)){
	    $min = array_search(min($Q), $Q);
	    if($min == $end) break;
	    foreach($d_arr[$min] as $key=>$val) if(!empty($Q[$key]) && $Q[$min] + $val < $Q[$key]) {
	        $Q[$key] = $Q[$min] + $val;
	        $S[$key] = array($min, $Q[$key]);
	    }
	    unset($Q[$min]);
	}

	$path = array();
	$pos = $end;
	while($pos != $start){
	    $path[] = $pos;
	    $pos = $S[$pos][0];
	}
	$path[] = $start;
	$path = array_reverse($path);

	return $S[$end][1];

	// echo "<br> 거리 : ".$S[$end][1];
	// echo "<br> 경로 : ".implode('->', $path);
}

?>