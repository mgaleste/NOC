<?php 

function getCompressedData($data){
    ob_start();
    // check to see if $data is a file
    if(file_exists($data)){
        // include file into output buffer
        include($data);
    }
    else{
        // echo string to output buffer
        echo $data;
    }
    // crunch buffer data
    $data=preg_replace("/(rn|)/","",ob_get_contents());
    // clean up output buffer
    ob_end_clean();
    // return data
    return $data;
} 

function compress($data){		
  ob_start();  // start output buffer  
  if(file_exists($data)){     // check to see if $data is a file
    include($data);  // include file into output buffer
  }else{    
    echo $data;  // echo string to output buffer
  } 

  // Get the current buffer and clean it, as well as remove any
  // newline/carrage return from the output
	//$data = ob_get_contents();
	
	$data	=	preg_replace("/(rn|)/","",ob_get_contents());

  // check if browser supports gzip encoding
  if(strstr($_SERVER['HTTP_ACCEPT_ENCODING'],'gzip')){
		// crunch content & compress data with gzip
		$compressed 	=	gzencode($data, 9);
		$data 			=	$compressed;	     
		header('Content-Encoding: gzip');	  // send http header
  }
    $data = ob_get_clean();
  // return data in the proper format
  return $data; 
}

 
function generate_resized_image(){
$max_dimension = 800; // Max new width or height, can not exceed this value.
//$dir = "./images/"; // Directory to save resized image. (Include a trailing slash - /)
$dir = "../uploads/brands/thumb/"; // Directory to save resized image. (Include a trailing slash - /)
// Collect the post variables.
$postvars = array(
"image"    => trim($_FILES["image"]["name"]),
"image_tmp"    => $_FILES["image"]["tmp_name"],
"image_size"    => (int)$_FILES["image"]["size"],
//"image_max_width"    => (int)$_POST["image_max_width"],
//"image_max_height"   => (int)$_POST["image_max_height"]
"image_max_width"    => 500,
"image_max_height"   => 500
);
// Array of valid extensions.
$valid_exts = array("jpg","jpeg","gif","png");
// Select the extension from the file.
$ext = end(explode(".",strtolower(trim($_FILES["image"]["name"]))));
// Check not larger than 175kb.
if($postvars["image_size"] <= 179200){
// Check is valid extension.
if(in_array($ext,$valid_exts)){
if($ext == "jpg" || $ext == "jpeg"){
$image = imagecreatefromjpeg($postvars["image_tmp"]);
}
else if($ext == "gif"){
$image = imagecreatefromgif($postvars["image_tmp"]);
}
else if($ext == "png"){
$image = imagecreatefrompng($postvars["image_tmp"]);
}
// Grab the width and height of the image.
list($width,$height) = getimagesize($postvars["image_tmp"]);
// If the max width input is greater than max height we base the new image off of that, otherwise we
// use the max height input.
// We get the other dimension by multiplying the quotient of the new width or height divided by
// the old width or height.
if($postvars["image_max_width"] > $postvars["image_max_height"]){
if($postvars["image_max_width"] > $max_dimension){
$newwidth = $max_dimension;
} else {
$newwidth = $postvars["image_max_width"];
}
$newheight = ($newwidth / $width) * $height;
} else {
if($postvars["image_max_height"] > $max_dimension){
$newheight = $max_dimension;
} else {
$newheight = $postvars["image_max_height"];
}
$newwidth = ($newheight / $height) * $width;
}
// Create temporary image file.
$tmp = imagecreatetruecolor($newwidth,$newheight);

$black = imagecolorallocate($filenamerep2, 0, 0, 0);
imagecolortransparent($filenamerep2, $black);


// Copy the image to one with the new width and height.
imagecopyresampled($tmp,$image,0,0,0,0,$newwidth,$newheight,$width,$height);
// Create random 4 digit number for filename.
$rand = rand(1000,9999);
$filename = $dir.$rand.$postvars["image"];
// Create image file with 100% quality.
imagejpeg($tmp,$filename,100);
return "<strong>Image Preview:</strong><br/>
<img src=\"".$filename."\" border=\"0\" title=\"Resized  Image Preview\" style=\"padding: 4px 0px 4px 0px;background-color:#e0e0e0\" /><br/>
Resized image successfully generated. <a href=\"".$filename."\" target=\"_blank\" name=\"Download your resized image now!\">Click here to download your image.</a>";
imagedestroy($image);
imagedestroy($tmp);
} else {
return "File size too large. Max allowed file size is 175kb.";
}
} else {
return "Invalid file type. You must upload an image file. (jpg, jpeg, gif, png).";
}
}



function sanitizeInput($input)
{    
        if (get_magic_quotes_gpc()){
			$input = stripslashes($input);
		}
		
        $input = trim($input);
        //$input = htmlentities($input);
		$input  = htmlentities($input, ENT_QUOTES, 'UTF-8');
        //$input = mysql_escape_string($input);
		
		$input = no_magic_quotes($input);
		
		return $input;
}


function rep_under($str){
	$data = '';
	$data = str_replace('_', ' ', $str);
	return ucwords($data);
}
	
function no_magic_quotes($query) {
        $data = explode("\\",$query);
        $cleaned = implode("",$data);
        return $cleaned;
}


function convertFromBytes($value){
						if(strlen($value)<=6){
							$kb = $value / 1024 ; //convert from bytes to kilobytes
							$temp = round($kb,2)." KB";
						}
						if(strlen($value)>6 && $kb>0){
							$mb = $b / 1024 ; //convert from kilobytes to megabytes
							$temp = round($mb,2)." MB";
						}
						if(substr($temp,strlen($temp)-2,2)=="KB"){
							$temp2=explode(" ",$temp);
							return round($temp2[0])." KB";
						}else{
							return $temp;
						}
					}

function formatDate($val)
{
$arr = explode('-', $val);
return date('F d, Y', mktime(0,0,0, $arr[1], $arr[2], $arr[0]));
}

function formatDate1($val)
{
$arr = explode('-', $val);
return date(' jS \\d\a\y \o\f, F Y', mktime(0,0,0, $arr[1], $arr[2], $arr[0]));
}

function formatDate2($val)
{
$arr = explode('-', $val);
return date('mdy', mktime(0,0,0, $arr[1], $arr[2], $arr[0]));
}

      function unhtmlentities($cadena){
	      // reemplazar entidades numericas
	      $cadena = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $cadena);
	      $cadena = preg_replace('~&#([0-9]+);~e', 'chr(\\1)', $cadena);
	      // reemplazar entidades literales
	      $trans_tbl = get_html_translation_table(HTML_ENTITIES);
	      $trans_tbl = array_flip($trans_tbl);   
	      return strtr($cadena, $trans_tbl);   
      }
	  
	  
	function CheckURL($url) {
	     if(preg_match('~((http|https|ftp|ftps)://|www.)(.+?)~',$url)) 
	          return true;
	     else 
	          return false;
	}

	function isAlpha($text)
	{  
	   $string = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMN\'OPQRSTUVWXYZ_- &amp;";
	   for($i=0; $i<strlen($text);$i++)
	     if(!substr_count($string,$text[$i])) // if 0 not alpha
	         return false;
	   return true;
	}
	
	

	function isNumeric($text)
	{  
	   $string = "1234567890.', - ";
	   for($i=0; $i<strlen($text);$i++)
	      if(!substr_count($string,$text[$i]))// if 0 not numeric
	         return false;
	   return true;
	}
	
	function check_date($date) {
    if(strlen($date) == 10) {
        $pattern = '/\.|\/|-/i';    // . or / or -
        preg_match($pattern, $date, $char);
       
        $array = preg_split($pattern, $date, -1, PREG_SPLIT_NO_EMPTY);
       

        // yyyy-mm-dd    # iso 8601
        if(strlen($array[0]) == 4 && $char[0] == "-") {
            $month = $array[1];
            $day = $array[2];
            $year = $array[0];
        }
        if(checkdate($month, $day, $year)) {    //Validate Gregorian date
            return TRUE;
       
        } else {
            return FALSE;
        }
    }else {
        return FALSE;    // more or less 10 chars
    }
	}

	function isAlphaNumeric($text)
	{ 
	   $i=0;
	   while(strlen($text)>$i)
	   {  $bool=false;
	      if(isNumeric($text[$i]))
	         $bool=true;
	      if(isAlpha($text[$i]))
	         $bool=true;
	      $i++;
	      if(!$bool)
	         return false;
	   }
	   return true;
	}

	function isEmail($email)
	{
		return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i"
			,$email));
	}
	
	function saveImage($im,$maxwidth,$maxheight,$name,$picdir)
	{	
		$RESIZEHEIGHT = $RESIZEWIDTH ="";
		$width = imagesx($im);
		$height = imagesy($im);
	    if(($maxwidth && $width > $maxwidth) || ($maxheight && $height > $maxheight))
	    {	if($maxwidth && $width > $maxwidth)
	    	{	$widthratio = $maxwidth/$width;
	           	$RESIZEWIDTH=true;
	        }
	        if($maxheight && $height > $maxheight)
	        {	$heightratio = $maxheight/$height;
	           	$RESIZEHEIGHT=true;
	        }
	        if($RESIZEWIDTH && $RESIZEHEIGHT)
	        {	if($widthratio < $heightratio){
	            	$ratio = $widthratio;
	           	}else{
	              	$ratio = $heightratio;
	           	}
	        }elseif($RESIZEWIDTH){
	              $ratio = $widthratio;
	        }elseif($RESIZEHEIGHT){
	           $ratio = $heightratio;
	        }
	        $newwidth = $width * $ratio;
	        $newheight = $height * $ratio;
	        
			
			
	        if(function_exists("imagecopyresampled")){
	        	$newim = imagecreatetruecolor($maxwidth, $maxheight);
	        	//$newim = imagecreatetruecolor($newwidth, $newheight);
				
				$white = imagecolorallocate($newim, 255, 255, 255);
				///imagefilledrectangle($newim, 0, 0, $newwidth, $newheight, $white);
				imagefilledrectangle($newim, 0, 0, $maxwidth, $maxheight, $white);
				
	            imagecopyresampled($newim, $im, 0, 0, 0, 0, $maxwidth, $maxheight, $width, $height);
	            //imagecopyresampled($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
	        }else{
	           	//$newim = imagecreate($newwidth, $newheight);
	           	$newim = imagecreate($maxwidth, $maxheight);
				
				$white = imagecolorallocate($newim, 255, 255, 255);
				//imagefilledrectangle($newim, 0, 0, $newwidth, $newheight, $white);
				imagefilledrectangle($newim, 0, 0, $maxwidth, $maxheight, $white);
	            
				imagecopyresized($newim, $im, 0, 0, 0, 0, $maxwidth, $maxheight, $width, $height);
				//imagecopyresized($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
	        }
	        //ImageJpeg ($newim,$picdir."/".$name.".jpg");
	        //ImageJpeg ($newim,$picdir."/".$name.".jpg");
			
			ImageJpeg ($newim,$picdir."/".$name);
	        //ImageJpeg ($newim,$picdir."/".$name);
	       	ImageDestroy ($newim);
	     }else{
	        ImageJpeg ($im,$picdir."/".$name);
	        //ImageJpeg ($im,$picdir."/".$name . ".jpg");
	     }
	}

function generateTCode(){
	$salt = "abcdefghijklmnopqrstuvwxyz123456789";
	srand((double)microtime()*1000000);
	$i = 0;
	while($i < 15){
	    $num = rand() % 33;
	    $tmp = substr($salt, $num, 1);
        $pass = $pass . $tmp;
	    $i++;
	}
		
	if(strlen($pass) > 10){
	  	$pass = substr($pass , 0, 10);
	}
	return $pass;
}

function generatePassword($size=9, $power=0) {
    $vowels = 'aeuy';
    $randconstant = 'bdghjmnpqrstvz';
    if ($power & 1) {
        $randconstant .= 'BDGHJLMNPQRSTVWXZ';
    }
    if ($power & 2) {
        $vowels .= "AEUY";
    }
    if ($power & 4) {
        $randconstant .= '23456789';
    }
    if ($power & 8) {
        $randconstant .= '@#$%';
    }

    $Randpassword = '';
    $alt = time() % 2;
    for ($i = 0; $i < $size; $i++) {
        if ($alt == 1) {
            $Randpassword .= $randconstant[(rand() % strlen($randconstant))];
            $alt = 0;
        } else {
            $Randpassword .= $vowels[(rand() % strlen($vowels))];
            $alt = 1;
        }
    }
    return $Randpassword;
}	



function stripHTML($string){
	$string = stripslashes($string);
	$new_string = preg_replace("(\<(/?[^\>]+)\>)","",$string);
	return wordwrap(substr($new_string,0,500),35)." ...";
}

function getImg($string){
	$img_details = '';
	$string = stripslashes($string);
	preg_match("(\<(/?[img]+[^\>]+)\>)",$string,$matches);	
	$img_details = $matches[1];	
	$pattern = array('/(width="\d+")/','/(height="\d+")/');
	$replace = array('width="150px"','');
	$img_out = preg_replace($pattern,$replace,$img_details);	
	return "<".$img_out.">";
}

function getID($tablename)
	    {   $sql = "SELECT * FROM ".$tablename." ORDER BY id DESC" ;
			$c = new connection();
			$c -> qselectDb($sql);
			if($c->fetchRs())
	            $id = $c->rs['id'] + 1 ;
	        else
	            $id = 1 ;
	        return $id ;
	    }
		
function getIDCustom($tablename,$idField)
	    {   $sql = "SELECT * FROM ".$tablename." ORDER BY $idField DESC" ;
			$c = new connection();
			$c -> qselectDb($sql);
			if($c->fetchRs())
	            $id = $c->rs[$idField] + 1 ;
	        else
	            $id = 1 ;
	        return $id ;
	    }
		
function showLog($taskdone,$tablename,$user){
		$aid=getID($tablename);				
						
		$sql 		= "";					
		//$admin_uid 	= $_SESSION['gp_id'];
		$stamp = strtotime("now");
		$datetime 	= date("Y-m-d H:i:s",$stamp);
			
		//$sql   = 	"UPDATE $table SET $field='".$value."'";
		$sql   = "INSERT INTO $tablename (id,userName,datetime,activity) VALUES ('".$aid."','".$user."','".$datetime."','$taskdone')";				
		
		$c = new connection();
		$c -> qexecuteDb($sql);
		return $sql;		
}	

function LogUpdate($taskdone,$tablename,$user,$section){
		$aid=getID($tablename);				
						
		$sql 		= "";					
		//$admin_uid 	= $_SESSION['gp_id'];
		$datetime 	= date("Y-m-d H:i:s");
			
		//$sql   = 	"UPDATE $table SET $field='".$value."'";
		$sql   = "INSERT INTO $tablename (id,user,adatetime,activity,section) VALUES ('".$aid."','".$user."','".$datetime."','".$taskdone."','".$section."')";				
		
		$c = new connection();
		$c -> qexecuteDb($sql);
		return $sql;		
}	

function makeURL($url){
	parse_str($url, $parsed);
	if(isset($parsed['page'])){
	unset($parsed['page']);
	}
	if(isset($parsed['items'])){
	unset($parsed['items']);
	}
	return http_build_query($parsed);
}	

function makeURL2($url,$toparse){
	parse_str($url, $parsed);
	if(isset($parsed[$toparse])){
	unset($parsed[$toparse]);
	}
	//echo http_build_query($parsed);
	return http_build_query($parsed);
}	

function URL_Parser($url,$parsing_array){
	parse_str($url, $parsed);
	foreach($parsing_array as $toparse){
		if(isset($parsed[$toparse])){
		unset($parsed[$toparse]);
		}
	}

	return http_build_query($parsed);
	
}

function double_escape($string){
 $escapechars = array("'","\\");
 $escape_rep_chars = array("''","\\\\");
 $new_str = str_replace($escapechars,$escape_rep_chars,$string);
 return $new_str;
}


function getCreator($id){
$con = new connection();
$con->qselectDb("SELECT * FROM users WHERE id='$id'");
if($con->fetchRs()){
return $con->rs['lastname'] .", ".$con->rs['firstname'];
//return 1;
}
return false;
}

function getPic($string,$width){
	$img_details = '';
	$string = stripslashes($string);	
	preg_match("(\<(/?[img]+[^\>]+)\>)",$string,$matches);	
	if(!empty($matches)){
			$img_details = $matches[1];				
			$pattern = array('/(width="\d+")/','/(height="\d+")/');
			$replace = array('width="'.$width.'"','');
			$img_out = preg_replace($pattern,$replace,$img_details);
			return "<".$img_out." border=\"0\">";
	}else{
	//return "<".$img_out." border=\"0\">";
		return "";
	}
}

function addLeadingZero($id,$maxlen)
{
	if(strlen($id)>3)
		return $id ;
		
	$newid = "" ;
	$len_id = strlen($id);
	$exclen = $maxlen - $len_id ;
	if($exclen>0)
	{
		for($x=0;$x<$exclen;$x++)
		{
			$newid .= "0" ;
		}
		$newid .= $id ;
	}else{
		$newid = $id ;
	}
	return $newid ;
}

function dateDifference($startDate="",$endDate=""){
	//echo $startDate." <> ".$endDate;
	$num_of_days=0;   
	if($endDate!="" && $startDate!=""){
   		$num_of_days = (strtotime($endDate) - strtotime($startDate)) / 86400 + 1;
	}
   	return $num_of_days;   
}

?>	
