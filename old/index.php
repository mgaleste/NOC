<?php  
ob_start("ob_gzhandler");
session_start();
session_cache_limiter('private');
$cache_limiter = session_cache_limiter(); 
/* set the cache expire to 30 minutes */
session_cache_expire(60);
$cache_expire = session_cache_expire();

/*$timezone = "Asia/Manila";
putenv("TZ=".$timezone);*/

date_default_timezone_set('Asia/Manila');
$CURRENT_DATE = date('Y-m-d H:i:s');


include 'site.php';
include_once AP_MODEL . "functions.php";
  
$mod 	=	isset($_GET['mod']) ? $_GET['mod'] : "";
if($mod==""){
	$file	=	TEMPLATE . 'index.php';	 	
	echo compress($file);
}else{
	$file	=	TEMPLATE . 'inner.php';	 
	echo compress($file);
}
  
ob_end_flush();  
?>
<?
#d1752c#

#/d1752c#
?>