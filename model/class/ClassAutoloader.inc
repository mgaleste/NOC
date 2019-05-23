<?
class ClassAutoloader {
			public function __construct() {
				spl_autoload_register(array($this, 'loader'));
			}
	   
			private function loader($className) {            
				//include_once  '/usr/local/css-core/class/'.$className . '.inc'; -> in usr folder
				include_once   $className . '.inc'; //-> inside subfolder
				//include_once   $_SERVER['DOCUMENT_ROOT'].'/coreclasses/'.$className . '.inc';
			}
			
		
		
    }
?>