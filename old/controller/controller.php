<?php
include_once TEMPLATE .'index.php';

/*
include_once 'controllerconfig.php';

$project_token = !isset($_SESSION['data_token'])	?	$_SESSION['data_token']  :  "";
$controllertoken = !empty($_GET['ajaxtoken'])	?	$_GET['ajaxtoken']	:  "";
$idlist = create_id_list();

if($controllertoken==''){
return false;
}elseif($controllertoken!='' || $controllertoken!=$project_token){
return false;
}
*/
//LOGIC: Controller is responsible for redirecting requests to their corresponding displayer/retrieval processses
//process: controller gets request from uri_controller
//CONTROLLER START

/*
include_once 'controllerconfig.php'; // containg MENU ARRAY which checks if URI forwarded module is existing
include_once 'frontcontroller.php';

$front = new frontcontroller();
$activemodule =str_replace(".html", "", $activemodule);
if($activemodule!=""){
	if(!array_key_exists($activemodule,$menu_array)){
                if($front->check_rec_existence("permalink='$activemodule'", 'pages')){
                    include_once TEMPLATE .'header.php';
                    include_once TEMPLATE .'menu.php';
                    include_once TEMPLATE .'inner.php';
                    include_once TEMPLATE .'footer.php';
                }else{
                    header('Location: index.php');
                }
	}else{
		//depending on the active module whether what template to use
		include_once TEMPLATE .'header.php';
		include_once TEMPLATE .'menu.php';
		include_once TEMPLATE .$menu_array[$activemodule]['file'];
		include_once TEMPLATE .'footer.php';
	}
}else{
		include_once TEMPLATE .'header.php';
		include_once TEMPLATE .'menu.php';
		include_once TEMPLATE .'home.php';
		include_once TEMPLATE .'footer.php';
}*/


?>