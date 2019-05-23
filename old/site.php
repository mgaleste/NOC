<?		
	define('MAIN', '');	
	define('ADMIN', '');
	 
	//define('CLASSPATH', $_SERVER['DOCUMENT_ROOT'].'/coreclasses/');
	define('CLASSPATH', 'apanel/model/class/'); //- if inside a subfolder
	//define('CLASSPATH', '/usr/local/css-core/class/'); //- if in usr/ folder in the server
	
	/*******************APANEL*************************/	
	define('AP_CONTROLLER', 'apanel/controller/');	
	define('AP_MODEL', 'apanel/model/');	
	define('AP_VIEW', 'apanel/view/');	
	define('AP_MODULES', 'apanel/modules/');
		
	define('APANEL', 'apanel/');
	/*******************MAIN**************************/
	define('CONTROLLER', 'controller/');	
	define('MODEL', 'model/');	
	define('VIEW', 'view/');	
	define('UPLOADS', 'uploads/');	
	define('RESOURCES', 'resources/');	
	define('TEMPLATE', VIEW . 'templates/');	
	define('TEMPIMAGES', VIEW . 'templates/images/');	
	define('TEMPLATEPLUG', VIEW . 'templateplugins/');	
	define('PTEMPLATE', VIEW . 'plugintemplates/');	
	define('MODULEPLUGIN', 'moduleplugins/');	
	define('ALBUMFOLDER', 'uploads/gallery/albums/');
    define('ALBUMTHUMBSFOLDER', 'uploads/gallery/albums/thumbnails/');
    define('ALBUMTHUMBSPWFOLDER', 'uploads/gallery/albums/thumbnails_pw/');
    define('PHOTOFOLDER', 'uploads/gallery/albums/photos/');	
	   
	define('PLUGINS', 'plugins/');	
	 
	include_once RESOURCES . 'pagination.php';
	include_once RESOURCES . 'mail.class.php';
	require_once RESOURCES . 'phpthumb/ThumbLib.inc.php';
	 
	include_once AP_MODEL . 'connection.php';
	include_once AP_MODEL . 'advanced_functions.php';
	include_once AP_MODEL . 'dbconfig.php';
	include_once AP_MODEL . '/class/validationFunctions.inc';
	include_once AP_MODEL . '/class/validations.inc'; 
	include_once CLASSPATH . 'ClassAutoloader.inc';
	$autoloader		=	new ClassAutoloader();  
 
	
	?>
