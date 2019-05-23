<?php
ob_start(); 
include_once('./../../../site.php');
$recon 			=  new recordUpdate();
$caption 		= "";
$description 	= ""; 
$abouttitle		=  $_POST['title'];
$aboutcontent	=  $_POST['aboutcontent'];
$catid			=  $_POST['catid'];

$arrayValues['title'] 		= $abouttitle;
$arrayValues['content'] 	= $aboutcontent;
$arrayValues['catid'] 		= $catid;
$arrayValues['type'] 		= "about_us"; 

if(empty($errmsg)){
	$recon->insertRecord($arrayValues, "pages");  
}
 
ob_flush();
?>

 