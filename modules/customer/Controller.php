

<?php
include_once AP_MODEL."dbconfig.php";
include_once AP_MODEL . 'connection.php';

//variables to be used
	//recover DIR of this module
	$controller_var	= 	new controller_class();
	$mod_dir		=	$controller_var->retrieve_paths('customer','modular');
	//dir where this module is located
	$dir			=	$mod_dir['dir'];
	//dir where tpls are located
	$viewdir		=	$mod_dir['viewdir'];
	$imagesdir		=	$mod_dir['imagedir'];

	$con 			=	new connection();
 
$task 					= (!empty($_GET['task'])) ? $_GET['task'] : "";
$type 					= (!empty($_GET['type'])) ? $_GET['type'] : "";

 if(!empty($task)){ 
	include_once $dir . $type.'Model.php';
	include_once $viewdir . $type.'.tpl';
 }else{  	
	include_once $dir . $type.'Model.php';
	include_once $viewdir . $type.'list.tpl';	 
 }
?>


