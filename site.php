<?
/*************APANEL CONFIGURATION*********************/		
	$domain = $_SERVER['HTTP_HOST'];	
	$server_name = $_SERVER['SERVER_NAME'];
	define('SERVER_NAME',$server_name);	
	//define('SITEPATH', '/core/'); //if subfolder
	define('SITEPATH', '/imagead/'); //if no subfolder
	
	define('CLASSPATH', 'model/class/'); //- if inside folder
	//define('CLASSPATH', $_SERVER['DOCUMENT_ROOT'].'/coreclasses/');	
	//define('CLASSPATH', '/usr/local/css-core/class/'); //- if in usr folder 
	  
	define('MAIN', $domain);
	define('ADMIN', $domain.'/apanel/');
	define('AP_PLUGINS', 'plugins/'); 	
	
	define('UPLOADS', 'uploads/'); 
	define('AP_MODEL', 'model/');
	define('AP_CONTROLLER', 'controller/');	
	define('AP_VIEW', 'view/');	
	define('AP_IMAGES', AP_VIEW.'images/');		
	define('MODIMAGES', AP_VIEW.'template1/moduleicons/');
	define('AP_CSS', AP_VIEW.'template1/css/');
	
	define('TEMPLATE', 'view/template1/');	              
	define('IMAGES', AP_VIEW.'template1/images/');
	define('AP_MODULES', 'modules/');
	
	define ('AP_MOD_ICONS','view/template1/moduleicons/');
	
	define('AP_MOD_HOME', AP_MODULES.'home/');
	define('AP_MOD_LOGIN', AP_MODULES.'login/');
	
	include_once AP_MODEL . 'dbconfig.php';
	include_once AP_MODEL . 'connection.php';
	include_once AP_MODEL . 'setting.php';
	include_once AP_MODEL . 'functions.php';
	include_once AP_MODEL . 'page_functions.php';
	include_once AP_MODEL . 'setup.php';  
	include_once AP_MODEL . 'class.phpmailer.php'; 	
	include_once AP_MODEL . 'controller_class.php';
	include_once AP_MODEL . 'pagination.php';
	include_once AP_MODEL . 'record.php';	
	include_once AP_MODEL . 'advanced_functions.php';
	include_once AP_MODEL . 'pclzip.lib.php';
	include_once AP_PLUGINS . 'pdf/mpdf.php';
	include_once AP_PLUGINS . 'pdf/fpdf/fpdf.php';
/*******************************************************************/	
 	 
	
	include_once CLASSPATH . 'ClassAutoloader.inc';
	$autoloader	 =	new ClassAutoloader();  
	require_once AP_PLUGINS . 'phpthumb/ThumbLib.inc.php';
 	 
?>
