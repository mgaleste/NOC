<?php
	header('Content-type: application/json');
	
	ob_start();
	session_start();
	include_once '../site.php';
	
	$recon 				= new recordUpdate();
	
	$searchkey			= isset($_POST['searchkey'])  ?  htmlentities($_POST['searchkey'])  : "";
	$mod				= isset($_POST['mod'])  ?  htmlentities($_POST['mod'])  : "";
	$type				= isset($_POST['type'])  ?  htmlentities($_POST['type'])  : "";
	$pg					= isset($_POST['pg'])  ?  htmlentities($_POST['pg'])  : 0;
	
	$items 				= (isset($_POST['items']))?  $_POST['items'] : 20;
	$recon->setItems($items);
	
	$tablerow			= "";
	$query				= "";
	
	$query				.= "SELECT agencyCode, agencyName, address, phone1, phone2, phone3, fax1, fax2, systemStatus
						  FROM agency
						  WHERE (agencyCode != '' AND agencyCode != '-') ";
	
	$query				.= (!empty($searchkey)) ? " AND (agencyCode LIKE '%$searchkey%' OR agencyName LIKE '%$searchkey%' OR address LIKE '%$searchkey%') " : "";
	$list_comp				= $recon->paginationQuery($query);
	$result					= $list_comp['result'];
	$num_rows				= $list_comp['num_rows'];
	$PAGINATION_LINKS		= $list_comp['PAGINATION_LINKS'];
	$PAGINATION_INFO		= $list_comp['PAGINATION_INFO'];
	$PAGINATION_TOTALRECS	= $list_comp['PAGINATION_TOTALRECS'];
	
	if($num_rows > 0){
		$bgclass	= 'odd_row';
		for($i=0;$i<$num_rows;$i++){
			$agencyCode			= mysql_result($result, $i, "agencyCode");
			$agencyName			= mysql_result($result, $i, "agencyName");
			$address			= mysql_result($result, $i, "address");
			$phone1				= mysql_result($result, $i, "phone1");
			$phone2				= mysql_result($result, $i, "phone2");
			$phone3				= mysql_result($result, $i, "phone3");
			$fax1				= mysql_result($result, $i, "fax1");
			$fax2				= mysql_result($result, $i, "fax2");
			$systemStatus		= mysql_result($result, $i, "systemStatus");
			
			$link				= "index.php?mod=$mod&type=$type&task=view&sid=$agencyCode";
			$telephone			= ((!empty($phone1)) ? $phone1 : ((!empty($phone2) ? $phone2 : (!empty($phone3) ? $phone3 : ""))));
			$fax				= ((!empty($fax1)) ? $fax1 : ((!empty($fax2)) ? $fax2 : ""));
			
			$tablerow	.= '<tr class="'.$bgclass.' list_row" id="'.$link.'" onClick="viewRecord(event,this.id);" title="View Details of '.$agencyName.'">';
			$tablerow	.= '<td class="table_line2_left center"><a href="./popups/pdf/csf_report.php?sid='.$agencyCode.'" title="Print report for '.$agencyName.'"><img src="'.AP_MOD_ICONS.'report.gif"</a></td>';
			$tablerow	.= '<td class="table_line2_left">&nbsp;'.$agencyCode.'</td>';
			$tablerow	.= '<td class="table_line2_left">&nbsp;'.$agencyName.'</td>';
			$tablerow	.= '<td class="table_line2_left">&nbsp;'.$address.'</td>';
			$tablerow	.= '<td class="table_line2_left">&nbsp;'.$telephone.'</td>';
			$tablerow	.= '<td class="table_line2_left">&nbsp;'.$fax.'</td>';
			$tablerow	.= '<td class="table_line2_left_right">&nbsp;'.ucfirst($systemStatus).'</td>';
			$tablerow	.= '</tr>';
			
			$bgclass	 = ($bgclass == 'odd_row') ? 'even_row' : 'odd_row';
			
		}
	}else{
		$tablerow	.= '<tr>';
		$tablerow	.= '<td class=" table_line2_left_right center" colspan="7">- No Records Found -</td>';
		$tablerow	.= '</tr>';
	}
	
	$dataArray			= array('agencies'=>$tablerow);
	
	//echo $returnVal;
	
	echo json_encode($dataArray);
	exit;
	
	ob_end_flush();
?>
