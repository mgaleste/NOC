<?php

function display_child($parent, $level,$type,$editparent) 
	{		
		if(!empty($type)){
			$types = "type='$type' AND ";
		}else{
			$types = "";
		}
		$rec_conn3 = new connection();
		$rec_query = "SELECT * from `category` WHERE $types (parent='$parent' AND category!='-')";
		$rec_conn3->qselectDb($rec_query);	
						
		while($rec_conn3->fetchRs())
		{ 
			if($editparent == $rec_conn3->rs['id']){
				$selected = "selected";
			}else{
				$selected = "";
			}			
			echo '<option value="'.$rec_conn3->rs['id'].'" '.$selected.' >'.str_repeat('-',$level).'&nbsp;'.stripslashes($rec_conn3->rs['category']).'</option>';
			display_child($rec_conn3->rs['id'], $level+1,$type,$editparent);
		}
	}
	
function display_prodchild($parent, $level,$type,$editparent) 
	{		
			$types = "";
		
		$rec_conn3 = new connection();
		$rec_query = "SELECT * from `itemcategory` WHERE parent='$parent'";
		$rec_conn3->qselectDb($rec_query);	
						
		while($rec_conn3->fetchRs())
		{ 
			if($editparent == $rec_conn3->rs['id']){
				$selected = "selected";
			}else{
				$selected = "";
			}			
			echo '<option value="'.$rec_conn3->rs['id'].'" '.$selected.' >'.str_repeat('-',$level).'&nbsp;'.stripslashes($rec_conn3->rs['caption']).'</option>';
			display_prodchild($rec_conn3->rs['id'], $level+1,$type,$editparent);
		}
	}
	
	
	function display_aboutchild($parent, $level,$type,$editparent) 
	{		
			$types = "";
		
		$rec_conn3 = new connection();
		$rec_query = "SELECT * from `category` WHERE parent='$parent' AND type='$type'";
		$rec_conn3->qselectDb($rec_query);	
						
		while($rec_conn3->fetchRs())
		{ 
			if($editparent == $rec_conn3->rs['id']){
				$selected = "selected";
			}else{
				$selected = "";
			}			
			echo '<option value="'.$rec_conn3->rs['id'].'" '.$selected.' >'.str_repeat('-',$level).'&nbsp;'.stripslashes($rec_conn3->rs['caption']).'</option>';
			display_aboutchild($rec_conn3->rs['id'], $level+1,$type,$editparent);
		}
	}
	
function anchor($link, $disp_text, $extra) 
   {  
        $domain = get_domain(); 
        $link = $domain . $link; 
        $data = '<a href="'.$link.'"';  
        $data .= ' '.$extra; 
        $data .= '>';  
        $data .= $disp_text; 
        $data .= "</a>";  
      
        return $data;  
    }  

	

function limit_words($wordcount,$strbody){
	    $content = strip_tags($strbody);
		$chopped = explode(" ",$content);
		$chopped_content = array();
		$words_got = 0;
		
		$words_got = count($chopped);
		if($words_got > $wordcount){
			for($x = 0; $x < $wordcount; $x++){
			$chopped_content[] = $chopped[$x];
			}	
			return implode(" ",$chopped_content) . '';
		}else{
			return $content;
		}
}

function getURL(){
	$servername     = 	$_SERVER['SERVER_NAME'];
	$uri 			= 	substr($_SERVER['REQUEST_URI'], 1);
	$this_url		=	$servername.'/'.$uri;
	return $this_url;
}

function check_module($modulename){
	$advanced_conn 	= 	new connection();
	$advanced_query =	"SELECT * FROM modules WHERE modulename='$modulename'";
	$advanced_conn->qselectDb($advanced_query);
	if($advanced_conn->fetchRs()){
		return 1;
	}else{
		return 0;
	}
}

function check_custom_mod($modulename){
	$advanced_conn 	= 	new connection();
	$advanced_query =	"SELECT * FROM custom_mods WHERE modulename='$modulename' AND remarks=''";
	$advanced_conn->qselectDb($advanced_query);
	if($advanced_conn->fetchRs()){
		return 1;
	}else{
		return 0;
	}
}

function front_check($front_condition,$perma){
	$advanced_conn 	= 	new connection();
	$advanced_query =	"SELECT * FROM reference WHERE ref_name='customfront' AND name='$front_condition' AND remarks1='$perma'";
	$advanced_conn->qselectDb($advanced_query);
	if($advanced_conn->fetchRs()){
		return 1;
	}else{
		return 0;
	}

}

function return_pd_pagetypes(){
	$advanced_arr = array();
	$advanced_conn 	= 	new connection();
	$advanced_query =	"SELECT * FROM custom_mods WHERE modulename='$modulename' AND (remarks=='onepager' OR remarks=='multiple')";
	$advanced_conn->qselectDb($advanced_query);
	while($advanced_conn->fetchRs()){
		$advanced_arr[] = $advanced_conn->rs;
	}
	return $advanced_arr;
}

function eb6h($str){
$temp1 = base64_encode($str);
$eb6 = base64_encode($temp1);
return $eb6;
}


function db6h($str){ 
$temp1 = base64_decode($str);
$db6 = base64_decode($temp1);
return $db6;
}

function root_url(){

	$domain = $_SERVER['HTTP_HOST'];
	$protocol_array = explode('/',$_SERVER['SERVER_PROTOCOL']);
	$protocol = strtolower($protocol_array[0]);

	$path = str_replace( basename($_SERVER['SCRIPT_FILENAME']), '', $_SERVER['PHP_SELF'] );

	$url = $protocol.'://'.$domain.$path;

	return $url;
	}

function root_dir(){

	$dir = dirname($_SERVER['SCRIPT_FILENAME']).'/';

	return $dir;
}



?>