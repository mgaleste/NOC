<? 
	include_once AP_MODEL."page.php";
	include_once CLASSPATH."coreMaintenance.inc";
	
	$page 	= 	new Page(TEMPLATE."index.html");
	$core	=	new coreMaintenance();
	//Retrieve Meta Details
	$metaArr 	= array('main_title','logo','main_charset','main_description','main_keywords','main_author');
	$metaResult = $core->retrieveMetaSettings($metaArr);
	foreach($metaResult as $metaIndex => $metaValue){		
		$$metaArr[$metaIndex] = stripslashes(html_entity_decode($metaValue));
	}
	$_SESSION['main_title'] = $main_title;

	$page->replace_tags(array(
	  "title" 	=> $main_title,
	  "subtitle"=> $main_title,	  	  
	  "breadcrumbs"=> TEMPLATE."breadcrumbs.php",
	  "menu" 	=> TEMPLATE."menu.html", 
	  "right" 	=> AP_CONTROLLER."MainContentController.php",
	  "left" 	=> TEMPLATE."left.php",
	  "footer" 	=> TEMPLATE."footer.html",
	  "banner" 	=> TEMPLATE."banner.html",
	  "banner_img" => "logo.png",  
	  "banner_text" => "",
	  "banner_alt" 	=> "logo",
	  
	  "meta_charset" => $main_charset,
	  "meta_description" => $main_description,
	  "meta_keywords" => $main_keywords,
	  "meta_author" => $main_author
	));
	
	$page->replace_tags2(array(
		"plug" => AP_PLUGINS,
		"images" => IMAGES,
		"css" => AP_CSS,
		"year" => date('Y')
	));
	
	$page->output();
	

?>
