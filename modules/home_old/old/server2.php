<?php
ob_start(); 
include_once('./../../../site.php');
$recon 						=  new recordUpdate();
 
$programtitle				=  $_POST['programtitle'];
$programcontent				=  $_POST['programcontent'];
$programcatid				=  $_POST['programcatid'];

$arrayValues['title'] 		= $programtitle;
$arrayValues['content'] 	= $programcontent;
$arrayValues['catid'] 		= $programcatid;
$arrayValues['type'] 		= "programs"; 

if(empty($errmsg)){
	$recon->insertRecord($arrayValues, "pages");  
}
 
ob_flush();
?>

 