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
	$accountexec_table	= "signatories_account_executives";
	$destination_table	= "signatories_destination";
	$package_table		= "signatory_package_rate";
	
	$continue_flag		= 1;
	
	$task					= (isset($_POST['task']) && (!empty($_POST['task']))) ? htmlentities(addslashes(trim($_POST['task']))) : "";
	$agencyCode				= (isset($_POST['agencyCode']) && (!empty($_POST['agencyCode']))) ? htmlentities(addslashes(trim($_POST['agencyCode']))) : "";
	$agencySignatoryCode	= (isset($_POST['agencySignatoryCode']))  ?  $_POST['agencySignatoryCode'] : "";
	$agencySignatoryName	= (isset($_POST['agencySignatoryName']))  ?  $_POST['agencySignatoryName'] : "";
	$accountExecutiveCode	= (isset($_POST['accountExecutiveCode']))  ?  $_POST['accountExecutiveCode'] : "";
	$designation			= (isset($_POST['designation']))  ?  $_POST['designation'] : "";
	$phone					= (isset($_POST['phone']))  ?  $_POST['phone'] : "";
	$fax					= (isset($_POST['fax']))  ?  $_POST['fax'] : "";
	$email					= (isset($_POST['email']))  ?  $_POST['email'] : "";
	
	$username				= (isset($_POST['username']) && (!empty($_POST['username']))) ? htmlentities(addslashes(trim($_POST['username']))) : "";
	$password				= (isset($_POST['password']) && (!empty($_POST['password']))) ? htmlentities(addslashes(trim($_POST['password']))) : "";
	
	/**VALIDATION**/
	if(empty($agencySignatoryName) || is_null($agencySignatoryName)){
		$continue_flag	= 2;
		$errmsg[] 		= "Please specify at least one(1) signatory for this client.<br/>";
	}
	
	if($continue_flag == 1){
		$signatoryArray		= $_POST;
		$accountexecArray	= array();
		unset($signatoryArray['agencyCode']);
		unset($signatoryArray['agencySignatoryCode']);
		unset($signatoryArray['username']);
		
		$signatoryArray['agencyCode']			= array_fill(0,count($agencySignatoryName), htmlentities(addslashes(trim($agencyCode))));
		$signatoryArray['username']				= $signatoryArray['email'];
		$signatoryArray['enteredBy']			= array_fill(0,count($agencySignatoryName), $user);
		$signatoryArray['updateBy']				= array_fill(0,count($agencySignatoryName), $user);
		$signatoryArray['updateDate']			= array_fill(0,count($agencySignatoryName), $datetime);
		$updateprogram							= ($task == 'create') ? "CSF-New" : "CSF-Edit";
		$signatoryArray['updateProgram']		= array_fill(0,count($agencySignatoryName), $updateprogram);
		$signatoryArray['systemStatus']			= array_fill(0,count($agencySignatoryName), "active");
	
		$accountexecArray['accountExecutiveCode']	= $signatoryArray['accountExecutiveCode'];
		$accountexecArray['systemStatus']			= $signatoryArray['systemStatus'];
		$accountexecArray['enteredBy']				= $signatoryArray['enteredBy'];
		$accountexecArray['updateBy']				= $signatoryArray['updateBy'];
		$accountexecArray['updateDate']				= $signatoryArray['updateDate'];
		$accountexecArray['updateProgram']			= $signatoryArray['updateProgram'];
		
		if(!empty($signatoryArray) && $continue_flag == 1){
			unset($signatoryArray['signatories_stat']);
			unset($signatoryArray['task']);
			unset($signatoryArray['accountExecutiveCode']);
			unset($signatoryArray['accountExecutiveName']);
		
			/**DELETE OLD RECORDS**/
			$oldSignatoryArray	= $recon->retrieveEntry($signatory_table,array('agencySignatoryCode'),""," agencyCode = '".$agencyCode."' ");
			if(!empty($oldSignatoryArray)){
				$oldSignatoryList	= (!empty($oldSignatoryArray) && !is_null($oldSignatoryArray)) ? "'".implode("','",$oldSignatoryArray)."'" : "";
		
				$recon->deleteRecord($package_table," agencySignatoryCode IN($oldSignatoryList) ");
				$recon->deleteRecord($destination_table," agencySignatoryCode IN($oldSignatoryList) ");
				$recon->deleteRecord($accountexec_table," agencySignatoryCode IN($oldSignatoryList) ");
				$recon->deleteRecord($signatory_table," agencyCode = '".$agencyCode."' ");
			}
			
			/**GENERATE TEMPORARY SIGNATORY CODES**/
			
			$signatoryId		= $recon->GetValue("agencySignatoryCode",$signatory_table," agencySignatoryCode != '' GROUP BY agencySignatoryCode ORDER BY agencySignatoryCode DESC LIMIT 1");
			$accountexecId		= $recon->GetValue("signatoriesAECode",$accountexec_table," signatoriesAECode != '' GROUP BY signatoriesAECode ORDER BY signatoriesAECode DESC LIMIT 1 ");
	
			$signatoryId		= (!empty($signatoryId)) ? (str_replace("SIG","",$signatoryId)+1)  : 0;
			$accountexecId		= (!empty($accountexecId)) ? (str_replace("AE","",$accountexecId)+1) : 0;
	
			$signatorykeys		= range(0,(count($agencySignatoryName)-1));
			$signatorycodes		= range($signatoryId,(count($agencySignatoryName)-1) + $signatoryId);
			$signatoryarray		= array_combine($signatorykeys,$signatorycodes);

			foreach ($signatoryarray as $key => $val) {
				$signatoryarray[$key] = "SIG".$val;
			}

			/**GENERATE TEMPORARY ACCOUNT EXECUTIVE OF SIGNATORY CODES**/
			$accountexeckeys	= range(0,(count($agencySignatoryName)-1));
			$accountexeccodes	= range($accountexecId,(count($agencySignatoryName)-1) + $accountexecId);
			$accountexecarray	= array_combine($accountexeckeys,$accountexeccodes);

			foreach ($accountexecarray as $key => $val) {
				$accountexecarray[$key] = "AE".$val;
			}
	
			$signatoryArray['agencySignatoryCode']	= $signatoryarray;
			
			$accountexecArray['signatoriesAECode']		= $accountexecarray;
			$accountexecArray['agencySignatoryCode']	= $signatoryArray['agencySignatoryCode'];
		
			/**SAVE SIGNATORIES FOR AGENCY**/
			$continue_flag  = insertMultipleRecords($signatoryArray, $signatory_table, $agencySignatoryName);
			if($continue_flag == 1){
				/**SAVE ACCOUNT EXECUTIVES FOR SIGNATORIES**/
				$continue_flag  = insertMultipleRecords($accountexecArray, $accountexec_table, $agencySignatoryName);
			}
		
			if($continue_flag==0){
				$continue_flag	= 2;
				$errmsg[] 		= "The system has encountered an error in saving the specified signatory. Please contact admin to fix this issue.<br/>";
			}
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
