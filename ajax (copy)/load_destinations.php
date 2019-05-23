<?php
	header('Content-type: application/json');
	
	ob_start();
	session_start();
	include_once '../site.php';
	
	$recon 				= new recordUpdate();

	$signatory			= isset($_POST['signatory'])  ?  htmlentities($_POST['signatory'])  : "";
	$task				= isset($_POST['task'])  ?  htmlentities($_POST['task'])  : "";
	
	$tablerow			= "";
	
	$retrievedFields	= array('main.destinationCode as destinationCode','main.destinationName as destinationName',
								'sub.destinationCode as selected_destinationCode','sub.agencySignatoryCode as agencySignatoryCode');
	$retrievedArray		= $recon->retrieveEntry("destination as main",$retrievedFields," LEFT JOIN signatories_destination as sub USING(destinationCode) "," main.systemStatus = 'active' GROUP BY main.destinationCode ORDER BY main.destinationCode ");
	
	if(!empty($retrievedArray)){
		$bgclass	= 'odd_row';
		$disabled	= ($task != 'view') ? "" : "disabled";
		foreach($retrievedArray as $retrievedIndex => $retrievedValue){
			$$retrievedIndex 			= $retrievedValue;
			$retrieveArr				= explode('|',$$retrievedIndex);
			$destinationCode			= $retrieveArr[0];
			$destinationName			= $retrieveArr[1];
			$selected_destinationCode	= $retrieveArr[2];
			$agencySignatoryCode		= $retrieveArr[3];
			$selected					= ($selected_destinationCode == $destinationCode && $agencySignatoryCode == $signatory) ? "checked" : "";
			
			$tablerow	.= '<tr class="'.$bgclass.' list_row">';
			$tablerow	.= '<td style="width:5%;" class="center"><input type="checkbox" id="'.$retrievedIndex.'" name="destinationList[]" value="'.$destinationCode.'" '.$selected.' '.$disabled.' style="outline:none;"/></td>';
			$tablerow	.= '<td style="width:35%;" >'.$destinationCode.'</td>';
			$tablerow	.= '<td style="width:60%;">'.$destinationName.'</td>';
			$tablerow	.= '</tr>';
			
			$bgclass	 = ($bgclass == 'odd_row') ? 'even_row' : 'odd_row';
		}
	}else{
		$tablerow	.= '<tr>';
		$tablerow	.= '<td class="center">- No Records Found -</td>';
		$tablerow	.= '</tr>';
	}
	
	$dataArray			= array('destinations'=>$tablerow);
	
	//echo $returnVal;
	
	echo json_encode($dataArray);
	exit;
	
	ob_end_flush();
?>
