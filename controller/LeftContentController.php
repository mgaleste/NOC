<?php
 
if(empty($_GET['mod'])){
	$mod = "";
	//include_once AP_MOD_HOME . 'index.tpl';
	//include_once AP_MOD_HOME . 'index.php';		
}else{
	$mod = $_GET['mod'];
	$main_controller= new controller_class();
	$mod_dir = $main_controller->retrieve_paths($mod,'left');
	if($mod_dir!=false){
					include_once $mod_dir.'/view/left.html';
				}	
} 

?>
