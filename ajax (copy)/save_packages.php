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
	$package_table		= "signatory_package_rate";
	
	$continue_flag		= 1;
	
	$task					= (isset($_POST['task']) && (!empty($_POST['task']))) ? htmlentities(addslashes(trim($_POST['destinations_task']))) : "";
	$agencyCode				= (isset($_POST['agencyCode']))  ?  $_POST['agencyCode'] : "";
	$packageSignatoryCode	= (isset($_POST['packageSignatoryCode']))  ?  $_POST['packageSignatoryCode'] : "";
	$packageList			= (isset($_POST['packageCode']))  ?  $_POST['packageCode'] : "";
	$netAmounts				= (isset($_POST['netAmount']))  ?  $_POST['netAmount'] : "";
	$grossAmounts			= (isset($_POST['grossAmount']))  ?  $_POST['grossAmount'] : "";
	
	/**VALIDATION**/
	if(empty($packageSignatoryCode) || is_null($packageSignatoryCode)){
		$continue_flag	= 2;
		$errmsg[] 		= "Please assign exam(s) / package(s) to at least one(1) signatory.<br/>";
	}
	
	/**RE-ASSIGN VALUES TO A SINGLE ARRAY**/
	if(!empty($packageList)){
		$count		= 0;
		$packArray	= array();
		$netArray	= array();
		$grossArray	= array();
		foreach($packageList as $packageIndex => $packageValue){
			$packList	= explode(', ',$packageValue);
			$netList	= explode('|',$netAmounts[$packageIndex]);
			$grossList	= explode('|',$grossAmounts[$packageIndex]);
			
			$packArray[$packageSignatoryCode[$packageIndex]] 	= $packList;
			$netArray[$packageSignatoryCode[$packageIndex]] 	= $netList;
			$grossArray[$packageSignatoryCode[$packageIndex]] 	= $grossList;
			$count		+= count($packList);
		}
	}

	/**SET EACH ROW AS UNIQUE**/
	$count			= 0;
	$newSignArray	= array();
	$newPackArray	= array();
	$newNetArray	= array();
	$newGrossArray	= array();
	foreach($packArray as $packIndex => $signatory_packArray){
		foreach($signatory_packArray as $signatory_packIndex => $packValue){
			//$destSignatoryArray[$count]." : ".$destIndex." : ".$destValue."<br/>";
			$newSignArray[]		= $packIndex;
			$newPackArray[]		= $packValue;
			$newNetArray[]		= $netArray[$packIndex][$signatory_packIndex];
			$newGrossArray[]	= $grossArray[$packIndex][$signatory_packIndex];
			$count++;
		}
	}
	
	if($continue_flag == 1){
	
		$packageArray['signatoryCode']			= $newSignArray;
		$packageArray['packageCode']			= $newPackArray;
		$packageArray['netAmount']				= $newNetArray;
		$packageArray['grossAmount']			= $newGrossArray;
		$packageArray['enteredBy']				= array_fill(0,$count, $user);
		$packageArray['updateBy']				= array_fill(0,$count, $user);
		$packageArray['updateDate']				= array_fill(0,$count, $datetime);
		$updateprogram							= ($task == 'create') ? "CSF-New" : "CSF-Edit";
		$packageArray['updateProgram']			= array_fill(0,$count, $updateprogram);
		$packageArray['systemStatus']			= array_fill(0,$count, "active");
	
		if(!empty($packageArray) && $continue_flag == 1){
			unset($packageArray['packages_stat']);
			unset($packageArray['packages_task']);
			
			/**DELETE OLD RECORDS**/
			$oldSignatoryArray	= $recon->retrieveEntry($signatory_table,array('agencySignatoryCode'),""," agencyCode = '".htmlentities(addslashes(trim($agencyCode)))."' ");
			if(!empty($oldSignatoryArray)){
				$oldSignatoryList	= (!empty($oldSignatoryArray) && !is_null($oldSignatoryArray)) ? "'".implode("','",$oldSignatoryArray)."'" : "";
		
				$recon->deleteRecord($package_table," agencySignatoryCode IN($oldSignatoryList) ");
			}
			
			/**SAVE SIGNATORIES FOR AGENCY**/
			$continue_flag  = insertMultipleRecords($packageArray, $package_table, $newPackArray);
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
