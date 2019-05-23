<?php
	header('Content-type: application/json');
	
	ob_start();
	session_start();
	include_once '../site.php';
	
	$recon 				= new recordUpdate();
	$mform 				= new formMaintenance();

	$signatory			= isset($_POST['signatory'])  ?  htmlentities($_POST['signatory'])  : ""; 
	
	$tablerow			= "";
	$addCond			= "";
	if(!empty($signatory))
	{
		$destArray		= $recon->retrieveEntry("signatories_destination",array('destinationCode'),""," agencySignatoryCode = '$signatory' GROUP BY destinationCode ORDER BY destinationCode ");
		$destList		= (!empty($destArray)) ? "'".implode("','",$destArray)."'" : ""; 
		$addCond		.= " AND (main.destinationCode IN($destList) OR main.packageCode = 'ALL' OR signatoryCode = '$signatory' ) ";
	}
	
	$retrievedFields	= array('main.packageCode as packageCode','main.packageDescription as packageDescription',
								'main.netAmount','main.grossAmount',
								'(select dest.destinationName from destination as dest where dest.destinationCode = main.destinationCode)',
								'sub.packageCode as selected_packageCode','sub.netAmount as selected_netAmount',
								'sub.grossAmount as selected_grossAmount','sub.signatoryCode as agencySignatoryCode');
	$retrievedArray		= $recon->retrieveEntry("exam_package as main",$retrievedFields," LEFT JOIN signatory_package_rate as sub USING(packageCode) "," main.systemStatus = 'active' $addCond GROUP BY main.packageCode ORDER BY main.packageCode ");
	
	if(!empty($retrievedArray)){
		$bgclass	= 'odd_row';
		foreach($retrievedArray as $retrievedIndex => $retrievedValue){
			$$retrievedIndex 			= $retrievedValue;
			$retrieveArr				= explode('|',$$retrievedIndex);
			$packageCode				= $retrieveArr[0];
			$packageDescription			= $retrieveArr[1];
			$netAmount					= $retrieveArr[2];
			$grossAmount				= $retrieveArr[3];
			$destinationCode			= $retrieveArr[4];
			$selected_packageCode		= $retrieveArr[5];
			$selected_netAmount			= $retrieveArr[6];
			$selected_grossAmount		= $retrieveArr[7];
			$agencySignatoryCode		= $retrieveArr[8];
			
			$netAmount					= ($selected_netAmount > 0 && $signatory == $agencySignatoryCode) ? $selected_netAmount : $netAmount;
			$grossAmount				= ($selected_grossAmount > 0 && $signatory == $agencySignatoryCode) ? $selected_grossAmount : $grossAmount;
				
			$selected					= ($selected_packageCode == $packageCode && $agencySignatoryCode == $signatory) ? "checked" : "";
			
			$tablerow	.= '<tr class="'.$bgclass.' list_row">';
			$tablerow	.= '<input type="checkbox" id="packageCode'.$retrievedIndex.'" name="packageList[]" value="'.$packageCode.'" '.$selected.' style="visibility:hidden;"/><td style="width:15%;" >&nbsp;'.$packageCode.'</td>';
			$tablerow	.= '<td style="width:40%;" >&nbsp;'.$packageDescription.'</td>';
			$tablerow	.= '<td style="width:15%;" >&nbsp;'.$destinationCode.'</td>';
			$tablerow	.= '<td style="width:15%;" >'.$mform->inputBox('create','text','netList[]',number_format($netAmount,2),'flat_input width100 right','netAmount'.$retrievedIndex,'60','',' style="background-color:transparent;" onClick="selectAll(this.id);" onBlur="autoSelect(\''.$retrievedIndex.'\'); formatNumber(this.id);" onKeyPress="return isNumberKey(event,46);" ','1').'</td>';
			$tablerow	.= '<td style="width:15%;" >'.$mform->inputBox('create','text','grossList[]',number_format($grossAmount,2),'flat_input width100 right','grossAmount'.$retrievedIndex,'60','',' style="background-color:transparent;" onClick="selectAll(this.id);" onBlur="autoSelect(\''.$retrievedIndex.'\'); formatNumber(this.id);" onKeyPress="return isNumberKey(event,46);" ','1').'</td>';
			$tablerow	.= '</tr>';
			
			$bgclass	 = ($bgclass == 'odd_row') ? 'even_row' : 'odd_row';
		}
	}else{
		$tablerow	.= '<tr>';
		$tablerow	.= '<td class="center">- No Records Found -</td>';
		$tablerow	.= '</tr>';
	}
	
	$dataArray			= array('packages'=>$tablerow);
	
	//echo $returnVal;
	
	echo json_encode($dataArray);
	exit;
	
	ob_end_flush();
?>
