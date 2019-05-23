<?php
 
$core 	= new coreFunctions();

//variables to be used
	//recover DIR of this module
	$controller_var	=	new controller_class();
	$mod_dir		=	$controller_var->retrieve_paths('config','modular');
	//dir where this module is located
	$dir			=	$mod_dir['dir'];
	//dir where tpls are located
	$viewdir		=	$mod_dir['viewdir'];
	$imagesdir		=	$mod_dir['imagedir'];
 
	$task			=  	isset($_GET['task']) ? $_GET['task'] : "";		
	$type			=  	isset($_GET['type']) ? $_GET['type'] : "";		
	$mod			=  	isset($_GET['mod']) ? $_GET['mod'] : "";		
	
	include_once $dir . $type.'Model.php';
	include_once $viewdir . $type.'.tpl';
 
  
?>
