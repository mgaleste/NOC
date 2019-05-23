<?
	include_once AP_MODEL."page.php";
	include_once CLASSPATH."coreMaintenance.inc";
	
	$page 	= 	new Page(TEMPLATE."login.html");
	$core	=	new coreMaintenance();
	 
	//Retrieve Meta Details
	$metaArr 	= array('main_title','logo');
	$metaResult = $core->retrieveMetaSettings($metaArr);
	foreach($metaResult as $metaIndex => $metaValue){
		$$metaArr[$metaIndex] = stripslashes(html_entity_decode($metaValue));
	}
	  
	 
	$page->replace_tags(array(
	  "title" 	=> $main_title,	  
	  "login" => AP_CONTROLLER."MainContentController.php",
	  "footer" => TEMPLATE."footer.html",
	  "logo" => TEMPLATE."logo.html",
	  "banner_img" => "logo.png",  
	  "banner_text" => "",
	  "banner_alt" => "logo"	  
	));
	
	$page->replace_tags2(array(
		"plug" => AP_PLUGINS,
		"images" => IMAGES,
		"css" => AP_CSS,
		"year" => date('Y')
	));
	$page->output();
	
?>
