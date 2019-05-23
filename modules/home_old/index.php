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
//Show Right Panel  
$createaccordion   .= 	$plug->setCurrentModAccess($currentModuleId);
$createaccordion   .= 	$plug->retrieveDashBoard();
  
//show tinymce
include_once('view/template1/home_tinymceconfig.php'); 
 
 $createaccordion .= "<script>			
			(function(){ 
					$('dd').filter(':nth-child(n+4)').addClass('hide');					 
					$('dl').on('click', 'dt', function(){
						$(this)
							.next() 
								.slideDown(200)
								.siblings('dd')									 
									.slideUp(200);						
					});
			})();			
	</script>";

  
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
