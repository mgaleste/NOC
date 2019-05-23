<?php 
$core 	= new coreFunctions();
 
//variables to be used
	//recover DIR of this module
	$controller_var	=	new controller_class();
	$mod_dir		=	$controller_var->retrieve_paths('logs','modular');
	//dir where this module is located
	$dir			=	'modules/reports/';
	//dir where tpls are located
	$viewdir		=	'modules/reports/view/';
	$imagesdir		=	'modules/reports/images/';
	
	$task			=  	isset($_GET['task']) ? $_GET['task'] : "";		
	$type			=  	isset($_GET['type']) ? $_GET['type'] : "";		
	$mod			=  	isset($_GET['mod'])  ? $_GET['mod']  : "";
        $cat			=  	isset($_GET['cat'])  ? $_GET['cat']  : "";
	  
	 if(!empty($cat)){
		include_once $dir . $mod.'Model.php';
                include_once $viewdir . 'reportsmenu.tpl';
		include_once $viewdir . $cat.'list.tpl';
	 }else{
		include_once $dir . $mod.'Model.php';
                include_once $viewdir . 'reportsmenu.tpl';
		include_once $viewdir . $mod.'list.tpl';
	 }
		 
	
?>
