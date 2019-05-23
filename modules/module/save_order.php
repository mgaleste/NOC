<?php
 
include_once '../../site.php';
include_once '../../model/dbconfig.php';

$rowid 		= isset($_POST['rowid']) 	? $_POST['rowid'] 	: "";
$orderby	= isset($_POST['orderby']) 	? $_POST['orderby'] : ""; 
$arr['menuorder']	=	$orderby;
$rec = new recordUpdate();
$rec->updateRecord($arr,"modules","id='$rowid'");
 
?>


 