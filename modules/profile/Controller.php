<?php
 


//variables to be used
	//recover DIR of this module
	$controller_var= new controller_class();
	$mod_dir=$controller_var->retrieve_paths('profile','modular');
	//dir where this module is located
	$dir=$mod_dir['dir'];
	//dir where tpls are located
	$viewdir=$mod_dir['viewdir'];
	$imagesdir=$mod_dir['imagedir'];

$con = new connection();

if(empty($_GET['task'])){
	$task = "view";
}else{
	$task = $_GET['task'];
}

if(empty($_GET['type'])){
	$type = "info";
}else{
	$type = $_GET['type'];
} 


if($type=="pass"){
		include_once $dir . 'Model.php';
		include_once $viewdir . 'profile.tpl';
}elseif($type=="info"){

	 
		include_once $dir . 'Model.php';
		include_once $viewdir . 'profile.tpl';
 
 
	
}
?>

