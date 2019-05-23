<?php 
$core 	= new coreFunctions();
 
//variables to be used
	//recover DIR of this module
	
	//dir where this module is located
	$dir			=	'modules/map/';
	//dir where tpls are located
	$viewdir		=	'modules/map/view/';
	$imagesdir		=	'modules/map/images/';
	
	$task			=  	isset($_GET['task']) ? $_GET['task'] : "";		
	$type			=  	isset($_GET['type']) ? $_GET['type'] : "";		
	$mod			=  	isset($_GET['mod'])  ? $_GET['mod']  : "";		
	  
	 
		include_once $dir . $mod.'Model.php';
		include_once $viewdir . $mod.'.tpl';
	 
		 
	
?>
