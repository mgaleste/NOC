<?
require_once AP_MODEL."page.php";		
	$page = new Page(TEMPLATE."index3.html");
			$meta = new setup();
			$metas	=$meta->set_up_meta();
			if(!empty($metas)){
				$stitle 	= ($metas['site_title']!="") ? $metas['site_title'] : "NOC for ISP";
			}else{
				$stitle 	= "NOC for ISP";
			}
	$page->replace_tags(array(
	  "title" => "$stitle",
	  "menu" => TEMPLATE."menu.html", 
	  "right" => AP_CONTROLLER."MainContentController.php",
	  "left" => TEMPLATE."left.php",
	  "footer" => TEMPLATE."footer.html",
	  "banner" => TEMPLATE."banner.html",
	  "banner_img" => "jrplogo.jpg",  
	  "banner_text" => "",
	  "banner_alt" => "logo"  
	));
	
	$page->replace_tags2(array(
		"plug" => AP_PLUGINS,
		"images" => IMAGES,
		"css" => AP_CSS
	));
	
	$page->output();
	
?>