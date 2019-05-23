<?
$modulerecord 		= new record('albumitems');
$paginate_arr['addquery'] 		= ""; 
$paginate_arr['paginatequery'] 	= "SELECT id, modulecaption, modulename, type, imagepath FROM modules WHERE stat='active' AND remarks!='' ";
$paginate_arr['query'] 			= "ORDER BY menuorder";

//call the function  where you will pass your array of queries for the class' future use
$modulerecord->manage_record_queries($paginate_arr);
//get pagination results then assign to $article list
$list 					= $modulerecord->get_paginated_array(12);
$result 				= $list['result'];
$num_rows 				= $list['num_rows'];
$PAGINATION_LINKS 		= $list['PAGINATION_LINKS'];
$PAGINATION_INFO 		= $list['PAGINATION_INFO'];
$PAGINATION_TOTALRECS 	= $list['PAGINATION_TOTALRECS'];
$left_content 	= "";
  
/** ---------------------------------------------TEMPLATE PLUGIN MODULES---------------------------------------------* */
if ($PAGINATION_TOTALRECS != 0) {
        
        $left_content  .= "<table width=\"300px\" cellspacing=\"0\" align=\"center\" cellpadding=\"0\" border=\"0\">";
 
		$cols 	= 3; # Number of columns to display
        $colCtr = 0;
        for ($i = 0; $i < $num_rows; $i++) {
            $menuid 			= mysql_result($result, $i, "id");                       
            $modulecaption 		= mysql_result($result, $i, "modulecaption");            
            $modulename 		= mysql_result($result, $i, "modulename");            
            $type		 		= mysql_result($result, $i, "type");            
			 
			$imagepic 			= mysql_result($result, $i, "imagepath");    
			if (file_exists("./view/template1/moduleicons/$imagepic") && !empty($imagepic)) {
								$pic 		= "<img src=\"./view/template1/moduleicons/$imagepic\" width=\"80px\" border=\"0\">";
			}else{		
								$pic 		= "<img src=\"./view/template1/moduleicons/photo.png\" width=\"80px\" border=\"0\">";
			}
		 
            if ($i == 0) {
                $left_content .= "<tr><td align=\"left\">";
                $left_content .= "<table cellspacing=\"5\" cellpadding=\"0\" border=\"0\" align=\"left\">";
            }

            if ($colCtr % $cols == 0) {
                $left_content .= "<tr>";
            }			
		  
            $left_content .= "<td align=\"left\"  valign=\"top\">";
            $left_content .= "<table cellpadding=\"0\" cellspacing=\"0\"  border=\"0\"  align=\"center\" width=\"100px\" class=\"iconitem rounded-corners\">";
            $left_content .= "<tr><td></td></tr>";
            $left_content .= "<tr><td align=\"center\" valign=\"middle\" height=\"100px\"><a  href=\"index.php?mod=$modulename&type=$type\">$pic</a></td></tr>";
            $left_content .= "<tr><td align=\"center\" height=\"40px\" valign=\"top\"  class=\"album_title\"><a href=\"index.php?mod=$modulename&type=$type\">".stripslashes(ucwords($modulecaption))."</a></td></tr>";
            $left_content .= "</table>";
            $left_content .= "</td>";
            $colCtr++; 			
        }
        $left_content .= "</tr>";   		
        $left_content .= "</table>";
        $left_content .= "</td>";
        $left_content .= "</tr>" . "\r\n";
		
		$left_content .= "</table>"; 
 }
?>