<?php
	ob_start();
	session_start();
	include_once '../site.php';

	$recon 				= new recordUpdate();
	$validation_class 	= new validations();

	$required_fields	= array();
	$errmsg				= array();
	$user				= (isset($_SESSION['gp_username'])) ? $_SESSION['gp_username'] : "";
	$datetime			= date("Y-m-d H:i:s");
	$signatory_table	= "agency_signatories";
	$destinations_table	= "signatories_destination";
	
	$destSignatoryId	= $recon->GetValue("signatoriesDestCode",$destinations_table," signatoriesDestCode != '' GROUP BY signatoriesDestCode ORDER BY signatoriesDestCode DESC LIMIT 1 ");
	
	$destSignatoryId	= (!empty($destSignatoryId)) ? (str_replace("SIG_DES","",$destSignatoryId)+1) : 0;
	
	$continue_flag		= 1;
	
	$task					= (isset($_POST['destinations_task']) && (!empty($_POST['destinations_task']))) ? htmlentities(addslashes(trim($_POST['destinations_task']))) : "";
	$agencyCode				= (isset($_POST['agencyCode']))  ?  $_POST['agencyCode'] : "";
	$agencySignatoryCode	= (isset($_POST['agencySignatoryCode']))  ?  $_POST['agencySignatoryCode'] : "";
	$destinationList		= (isset($_POST['destinationCode']))  ?  $_POST['destinationCode'] : "";
	
	/**VALIDATION**/
	if(empty($agencySignatoryCode) || is_null($agencySignatoryCode)){
		$continue_flag	= 2;
		$errmsg[] 		= "Please assign destinations to at least one(1) signatory.<br/>";
	}
	
	/**RE-ASSIGN VALUES TO A SINGLE ARRAY**/
	if(!empty($destinationList)){
		$count	= 0;
		foreach($destinationList as $destinationListIndex => $destinationValue){
			$tempArray	= explode(', ',$destinationValue);
			$destArray[$agencySignatoryCode[$destinationListIndex]] = $tempArray;
			$count		+= count($tempArray);
		}
	}
	
	/**GENERATE TEMPORARY SIGNATORY DESTINATION CODES**/
	$destSignatoryKeys	= range(0,($count-1));
	$destSignatoryCodes	= range($destSignatoryId,(($count-1) + $destSignatoryId) );
	$destSignatoryArray	= array_combine($destSignatoryKeys,$destSignatoryCodes);

	foreach ($destSignatoryArray as $key => $val) {
		$destSignatoryArray[$key] = "SIG_DES".$val;
	}
	
	/**SET EACH ROW AS UNIQUE**/
	$count			= 0;
	$newSignArray	= array();
	$newDestArray	= array(); 
	foreach($destArray as $destIndex => $signatory_destArray){
		foreach($signatory_destArray as $signatory_destIndex => $destValue){
			//$destSignatoryArray[$count]." : ".$destIndex." : ".$destValue."<br/>";
			$newSignArray[]	= $destIndex;
			$newDestArray[]	= $destValue;
			$count++;
		}
	}
	
	//$continue_flag = 0;
	if($continue_flag == 1){
	
		$destinationArray['signatoriesDestCode']	= $destSignatoryArray;
		$destinationArray['destinationCode']		= $newDestArray;
		$destinationArray['agencySignatoryCode']	= $newSignArray;
		$destinationArray['enteredBy']				= array_fill(0,$count, $user);
		$destinationArray['updateBy']				= array_fill(0,$count, $user);
		$destinationArray['updateDate']				= array_fill(0,$count, $datetime);
		$updateprogram								= ($task == 'create') ? "CSF-New" : "CSF-Edit";
		$destinationArray['updateProgram']			= array_fill(0,$count, $updateprogram);
		$destinationArray['systemStatus']			= array_fill(0,$count, "active");
	
		if(!empty($destinationArray) && $continue_flag == 1){
			unset($destinationArray['destinations_stat']);
			unset($destinationArray['destinations_task']);
			
			/**DELETE OLD RECORDS**/
			$oldSignatoryArray	= $recon->retrieveEntry($signatory_table,array('agencySignatoryCode'),""," agencyCode = '".htmlentities(addslashes(trim($agencyCode)))."' ");
			if(!empty($oldSignatoryArray)){
				$oldSignatoryList	= (!empty($oldSignatoryArray) && !is_null($oldSignatoryArray)) ? "'".implode("','",$oldSignatoryArray)."'" : "";
		
				$recon->deleteRecord($destinations_table," agencySignatoryCode IN($oldSignatoryList) ");
			}
			
			/**SAVE SIGNATORIES FOR AGENCY**/
			$continue_flag  = insertMultipleRecords($destinationArray, $destinations_table, $newDestArray);
		}
	}
	
	function insertMultipleRecords($arrayValues, $table, $sampleArray)
	{
		$recon 				= new recordUpdate();
		$dataArray			= array();
		$finalInsertQuery	= "";
		$insertStatement	= "INSERT INTO $table (";
		
		foreach($arrayValues as $arrayIndex => $arrayValue){
			if(!empty($arrayValue) && !is_null($arrayValue)){
				$insertStatement 		.= $arrayIndex.',';
				$dataArray[$arrayIndex]  = $arrayValue;
			}
		}
		$insertStatement = substr($insertStatement, 0, -1);
		$insertStatement .= ')';
		
		$valueStatement		= " VALUES";
		foreach($sampleArray as $sampleIndex => $sampleValue){
			$valueStatement	 .= "(";
			if(!empty($dataArray) && !is_null($dataArray)){
				foreach($dataArray as $dataIndex => $dataValue){
					$valueStatement	 .= "'".$dataValue[$sampleIndex]."',";
				}
			}
			$valueStatement = substr($valueStatement, 0, -1);
			$valueStatement .= '),';
		}
		$valueStatement = substr($valueStatement, 0, -1);
		
		$finalInsertQuery	= $insertStatement.' '.$valueStatement;
		
		$result	= $recon->customizedQuery($finalInsertQuery);
		
		return (empty($result) || $result == '') ? 1 : 0;
	}	
	
	$error_messages = implode('', $errmsg);
	
	echo $continue_flag.'|'.$error_messages;

	ob_end_flush();
?>
