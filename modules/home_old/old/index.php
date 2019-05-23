<?

ob_start();
$recon 				= new recordUpdate();
$core 				= new coreFunctions();
$mform 				= new formMaintenance();
$plug 				= new pagePlugins();  
$modi 				= (isset($_GET['mod'])) ? $_GET['mod'] : "";
$id				 	= (isset($_GET['id'])) ? $_GET['id'] : "";

//LEFT ICON NAVIGATIONS
include_once('left.php'); 
$left 				= $left_content;
$right 			= "";
$createaccordion 	= "";
$latest 			= "";
/*************************************************************************************************/

	$createaccordion .= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"700px\" id=\"cleartable\">";
	 
	//NEWS
	$createaccordion .= "<tr><td class=\"latestaddedbar\"  id=\"togglenews\"><a href=\"#\">Create News</a></td></tr>";	
	$createaccordion .= "<tr><td id=\"newseditor\"  bgcolor=\"#ffffff\" class=\"toggleeditor\" width=\"700px\">";	
	
	$createaccordion .= $plug->pagePlugin("news");
	
	$createaccordion .= "</td></tr>";  
 	
	//PROMOS
	$createaccordion .= "<tr><td class=\"latestaddedbar\" id=\"togglepromos\"><a href=\"#\">Create Promos and Event</a></td></tr>";
	$createaccordion .= "<tr><td id=\"promoseditor\" bgcolor=\"#ffffff\"  class=\"toggleeditor\" width=\"700px\">";
	$createaccordion .= $plug->pagePlugin("promos_and_events");
	$createaccordion .= "</td></tr>"; 	
	 
	//PHOTO GALLERY
	$createaccordion .= "<tr><td class=\"latestaddedbar\"><a href=\"index.php?mod=gallery&type=album&task=create\">Create Photo Album</a></td></tr>";
	
	//VIDEO GALLERY
	$createaccordion .= "<tr><td class=\"latestaddedbar\"><a href=\"index.php?mod=gallery&type=video&task=create\">Create Video</a></td></tr>";	
	
	//TESTIMONIALS
	$createaccordion .= "<tr><td class=\"latestaddedbar\"  id=\"toggletestimonial\"><a href=\"#\">Create Testimonials</a></td></tr>";	
	$createaccordion .= "<tr><td id=\"testimonialeditor\"  bgcolor=\"#ffffff\" class=\"toggleeditor editorbottom\" width=\"700px\">";	 
	$createaccordion .= $plug->pagePlugin("testimonials");
	$createaccordion .= "</td></tr>"; 		 
	$createaccordion .= "</table>";	
	 
 
  
 $right .= $createaccordion;
 $right .= $latest;
 
   


 
 
 
 
 
 
 
/** ---------------------------------------------CONVERT TEMPLATE TAGS---------------------------------------------* */
$handle = fopen("modules/home/index.tpl", "r");
$contents = fread($handle, filesize("modules/home/index.tpl"));
fclose($handle);
 
$output = str_replace("{{left}}", $left, $contents);
$output = str_replace("{{right}}", $right, $output);
 

echo $output;

ob_end_flush();
?>
