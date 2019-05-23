<?
function loadMenuModule($template,$class){
	include_once MODULE .'menu/Model.php';
	
	$menu = new Menu();
	$menu->template = $template;
	$menu->menu_class = $class;
	
	include MODULE .'menu/Controller.php';
}


function loadModule($mod, $args, $class_call, $function_call){
	include_once MODULE .$mod.'/Model.php';
	include_once MODULE .$mod.'/Controller.php';
	
	$_CLASS = new $class_call();
	
	foreach($args as $val){
		$_CLASS->$val[0] = $val[1];
	}
	
	$_CLASS->$function_call();
}

function getContent($mod, $table, $id, $template){
	$c = new connection();
	
	$query = ($id != "")? "SELECT * FROM $table WHERE id = '$id'   ORDER BY id ASC": "SELECT * FROM $table WHERE 1=1 ORDER BY id ASC";
	
	$c->qselectDb($query);
	$content = $c->fetchRs();	
	
	outputContent($content, $template);
}

function outputContent($contentDetails, $template){
	$text['title'] = stripslashes($contentDetails['title']);
	$text['content'] = stripslashes($contentDetails['content']);

	
	$handle = fopen($template, "r");
	$contents = fread($handle, filesize($template));
	fclose($handle);
	$exchange = array("{{title}}","{{content}}");
	/*var_dump($contentDetails);
	var_dump($exchange);*/
	$output = str_replace($exchange,$text,$contents);
	echo $output;

}

function getContentArchive($mod, $table, $template){
	$c = new connection();
	
	$query = "SELECT * FROM $table ORDER BY id ASC";
	
	$c->qselectDb($query);
	while($c->fetchRs()){
		$content[] = $c->rs;
	}
		
	outputContentArchive($mod,$content, $template);
}

function outputContentArchive($mod,$content, $template){
	$lists = '<div><table cellpadding="0" cellspacing="0" border="0" align="left" width="100%">';
	
	foreach($content as $details){
			$aid = $details['id'];
			$title = stripslashes($details['title']);																		
		$lists .= '<tr><td valign="top" class="about_link"><a href="index.php?mod='.$mod.'&id='.$aid.'">'.$title.'</a></td></tr>';
	}
	
	$lists .= '</table></div>
	';
	
	$handle = fopen($template, "r");
	$contents = fread($handle, filesize($template));
	fclose($handle);

	$output = str_replace("{{listarchive}}",$lists,$contents);
	echo $output;
}

?>