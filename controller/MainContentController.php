<?php

if(isset($_SESSION['gp_has_logged'])){
			if(empty($_GET['mod'])){
				$mod = "";												
				include_once AP_MOD_HOME . 'index.php';			
			}else{
				$mod = $_GET['mod'];
                                if($mod != 'reports' && $mod != 'map'){
                                    $main_controller= new controller_class();
                                    $mod_dir = $main_controller->retrieve_paths($mod,'middlemain');

                                    if($mod_dir!=false){
                                            include_once $mod_dir.'Controller.php';
                                    }
                                }else{
                                    include_once 'modules/'.$mod.'/'.$mod.'Controller.php';
                                }
			}		
}else{		
			include_once AP_MOD_LOGIN . 'Controller.php';				
}
?>