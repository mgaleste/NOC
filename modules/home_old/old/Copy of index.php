<?
ob_start();
$recon 				=	new recordUpdate();
$core 				=	new coreFunctions();
$mform 				=	new formMaintenance();
$plug 				=	new pagePlugins();  
$modi 				=	(isset($_GET['mod'])) ? $_GET['mod'] : "";
$id				 	=	(isset($_GET['id'])) ? $_GET['id'] : "";
 
//LEFT ICON NAVIGATIONS
include_once('left.php'); 
$left 				=	$left_content;
$right 				=	"";
$createaccordion 	=	"";
$latest 			=	"";
 
/*************************************************************************************************/

	$createaccordion .= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"700px\" id=\"cleartable\">";	 
//NEWS
if(in_array(13,$currentModuleId)){	
	$createaccordion .= "<tr><td class=\"latestaddedbar\"  id=\"togglenews\"><a href=\"#\">Create Promo</a></td></tr>";	
	$createaccordion .= "<tr><td id=\"newseditor\"  bgcolor=\"#ffffff\" class=\"toggleeditor\" width=\"700px\">";	
	$moduleType 	  = "promos";
	$newsArr		  = array($moduleType.'publishdate',$moduleType.'publishtime',$moduleType.'images5',$moduleType.'title',$moduleType.'contents',$moduleType.'postingdate');
 	$createaccordion .= $plug->pagePlugin($moduleType,$newsArr,"news","trigger","date","trigger2","date2");
	$createaccordion .= "</td></tr>";  
 }	 
 
  

//PRODUCTS
if(in_array(25,$currentModuleId)){
 $createaccordion .= "<tr><td class=\"latestaddedbar\"  id=\"toggleitem\"><a href=\"#\">Create Product</a></td></tr>";	
 $createaccordion .= "<tr><td id=\"itemeditor\"  bgcolor=\"#ffffff\" class=\"toggleeditor\" width=\"700px\">";	
 $moduleTypes 	   = "item"; 
 $productsArr	   = array($moduleTypes.'publishdate',$moduleTypes.'publishtime',$moduleTypes.'catid',$moduleTypes.'subcatid',$moduleTypes.'subsubcatid',$moduleTypes.'brandid',$moduleTypes.'imagepath',$moduleTypes.'itemSDesc',$moduleTypes.'itemSize',$moduleTypes.'itemLDesc',$moduleTypes.'isnew',$moduleTypes.'isfeatured');
 $createaccordion .= $plug->productPlugin($moduleTypes,$productsArr,"item","trigger3","date3");
 $createaccordion .= "</td></tr>";
}
 
//FINANCIAL REPORTS
if(in_array(28,$currentModuleId)){	
	$createaccordion .= "<tr><td class=\"latestaddedbar\"><a href=\"index.php?mod=downloads&type=financial_reports&task=create\">Create Financial Report</a></td></tr>";	
}	
//DISCLOSURES
if(in_array(29,$currentModuleId)){	
	$createaccordion .= "<tr><td class=\"latestaddedbar\"><a href=\"index.php?mod=downloads&type=disclosures&task=create\">Create Disclosures</a></td></tr>";	
}
//STOCK INFORMATION
if(in_array(30,$currentModuleId)){	
	$createaccordion .= "<tr><td class=\"latestaddedbar\"><a href=\"index.php?mod=downloads&type=stock_information&task=create\">Create Stock Information</a></td></tr>";	
}



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
