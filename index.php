<?php 
ob_start("ob_gzhandler");
session_start();
session_cache_limiter('private');
$cache_limiter = session_cache_limiter();
	
/* set the cache expire to 30 minutes */
session_cache_expire(60);
$cache_expire = session_cache_expire();

$timezone = "Asia/Manila";
putenv("TZ=".$timezone);

include 'site.php';

if(isset($_SESSION['gp_has_logged'])){	 
	//include_once TEMPLATE.'layout_2.php';
	$file	=	TEMPLATE.'layout_2.php';
	echo compress($file);
}else{
	//include_once TEMPLATE.'layout_1.php';	
	$file	=	TEMPLATE.'layout_1.php';
	echo compress($file);
}

ob_end_flush();
?>

