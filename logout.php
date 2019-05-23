<?php
ob_start();
session_start();

include_once('site.php');
include_once("model/login.class.php");

$user_login =	new Login(); 
$core 		= 	new coreFunctions();				
$admin		=	$_SESSION['gp_username'];
$activity 	=	"Logout";
$core->logAdminTask($admin,"","logout",$activity);
	 					
$user_login->user_logout();	
header('Location: index.php');		
exit();       
ob_end_flush();
?>