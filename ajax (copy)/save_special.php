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
	$special_table		= "special_instructions";
	
	$specialId			= $recon->GetValue("instructionCode",$special_table," instructionCode != '' GROUP BY instructionCode ORDER BY instructionCode DESC LIMIT 1 ");
	
	$specialId			= (!empty($specialId)) ? (str_replace("AGE_SPE","",$specialId)+1) : 0;
	
	$continue_flag		= 1;
	
	$task				= (isset($_POST['special_task']) && (!empty($_POST['special_task']))) ? htmlentities(addslashes(trim($_POST['special_task']))) : "";
	$agencyCode			= (isset($_POST['agencyCode']) && (!empty($_POST['agencyCode']))) ? htmlentities(addslashes(trim($_POST['agencyCode']))) : "";
	$departmentCode		= (isset($_POST['departmentCode']))  ?  $_POST['departmentCode'] : "";
	$instructionDate	= (isset($_POST['instructionDate']))  ?  $_POST['instructionDate'] : "";
	$instruction		= (isset($_POST['instruction']))  ?  $_POST['instruction'] : "";
	$isPrint			= (isset($_POST['isPrint']))  ?  $_POST['isPrint'] : "";
	
	/**VALIDATION*
	if(empty($instruction) || is_null($instruction)){
		$continue_flag	= 2;
		$errmsg[] 		= "Please assign destinations to at least one(1) signatory.<br/>";
	}*/
	
	$count	= count($instruction);
	/**GENERATE TEMPORARY SIGNATORY DESTINATION CODES**/
	$specialKeys	= range(0,($count-1));
	$specialCodes	= range($specialId,(($count-1) + $specialId) );
	$specialArrayId	= array_combine($specialKeys,$specialCodes);

	foreach ($specialArrayId as $key => $val) {
		$specialArrayId[$key] = "AGE_SPE".$val;
	}
	
	//$continue_flag = 0;
	if($continue_flag == 1 && !empty($instruction)){
	
		$specialArray['instructionCode']	= $specialArrayId;
		$specialArray['agencyCode']			= array_fill(0,$count, $agencyCode);
		$specialArray['instructionDate']	= $instructionDate;
		$specialArray['instruction']		= $instruction;
		$specialArray['isPrint']			= $isPrint;
		$specialArray['departmentCode']		= $departmentCode;
		$specialArray['enteredBy']			= array_fill(0,$count, $user);
		$specialArray['updateBy']			= array_fill(0,$count, $user);
		$specialArray['updateDate']			= array_fill(0,$count, $datetime);
		$updateprogram						= ($task == 'create') ? "CSF-New" : "CSF-Edit";
		$specialArray['updateProgram']		= array_fill(0,$count, $updateprogram);
		$specialArray['systemStatus']		= array_fill(0,$count, "active");
	
		if(!empty($specialArray) && $continue_flag == 1){
			unset($specialArray['special_stat']);
			unset($specialArray['special_task']);
			
			/**DELETE OLD RECORDS*
			$oldSignatoryArray	= $recon->retrieveEntry($signatory_table,array('agencySignatoryCode'),""," agencyCode = '".htmlentities(addslashes(trim($agencyCode)))."' ");
			if(!empty($oldSignatoryArray)){
				$oldSignatoryList	= (!empty($oldSignatoryArray) && !is_null($oldSignatoryArray)) ? "'".implode("','",$oldSignatoryArray)."'" : "";
		
				//$recon->deleteRecord($destinations_table," agencySignatoryCode IN($oldSignatoryList) ");
			}*/
			
			/**SAVE SPECIAL INSTRUCTIONS FOR AGENCY**/
			$continue_flag  = insertMultipleRecords($specialArray, $special_table, $instruction);
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
					if($dataIndex == 'instructionDate'){
						$dataValue[$sampleIndex] = date("Y-m-d",strtotime($dataValue[$sampleIndex]));
					}else if($dataIndex == 'instruction'){
						$dataValue[$sampleIndex] = htmlentities(addslashes(trim($dataValue[$sampleIndex])));
					}
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
