<?php
 
//error_reporting(1);

//variables to be used
	//recover DIR of this module
	$controller_var= new controller_class();
	$mod_dir=$controller_var->retrieve_paths('forgot','modular');
	//dir where this module is located
	$dir=$mod_dir['dir'];
	//dir where tpls are located
	$viewdir=$mod_dir['viewdir'];
	$imagesdir=$mod_dir['imagedir'];

$con = new connection();

if(empty($_GET['mod'])){
	$mod = "";
}else{
	$mod = $_GET['mod'];
}

if($mod=='forgot'){
	$mod_dir=$controller_var->retrieve_paths('forgot','modular');
	//dir where this module is located
	$dir=$mod_dir['dir'];
	//dir where tpls are located
	$viewdir=$mod_dir['viewdir'];
	$imagesdir=$mod_dir['imagedir'];
	
	include_once $dir . 'Model.php';
	include_once $viewdir. 'forgot.tpl';
}else{
	include_once $dir. 'Model.php';
	include_once $viewdir . 'login.tpl';
}	
?>

