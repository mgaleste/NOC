<?php

$con = new connection();

if(empty($_GET['task'])){
	$task = "";
}else{
	$task = $_GET['task'];
} 

			include_once AP_MOD_ADMINLOGS . 'Model.php';
			include_once AP_MOD_ADMINLOGSVIEW . 'adminlogs.tpl';

	
?>
