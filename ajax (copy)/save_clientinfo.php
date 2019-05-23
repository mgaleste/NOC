<?php
	ob_start();
	session_start();
	include_once '../site.php';
	$recon 				= new recordUpdate();
	$validation_class 	= new validations();
	$username			= (isset($_SESSION['gp_username'])) ? $_SESSION['gp_username'] : "";
	$required_fields	= array();
	$errmsg				= array();
	$datetime			= date("Y-m-d H:i:s");
	$table				= "agency";
	$continue_flag		= 1;
	$task				= (isset($_POST['task']) && (!empty($_POST['task']))) ? htmlentities(addslashes(trim($_POST['task']))) : "";
	
	$systemStatus		= (isset($_POST['systemStatus']) && (!empty($_POST['systemStatus']))) ? htmlentities(addslashes(trim($_POST['systemStatus']))) : "ready";
	$agencyCode			= (isset($_POST['agencyCode']) && (!empty($_POST['agencyCode']))) ? htmlentities(addslashes(trim($_POST['agencyCode']))) : "";
	$agencyName			= (isset($_POST['agencyName']) && (!empty($_POST['agencyName']))) ? htmlentities(addslashes(trim($_POST['agencyName']))) : "";
	$startDate			= (isset($_POST['startDate']) && (!empty($_POST['startDate']))) ? htmlentities(addslashes(trim($_POST['startDate']))) : date("Y-m-d");
	$_POST['startDate'] = date("Y-m-d",strtotime($startDate));
	$address			= (isset($_POST['address']) && (!empty($_POST['address']))) ? htmlentities(addslashes(trim($_POST['address']))) : "";
	$phone1				= (isset($_POST['phone1']) && (!empty($_POST['phone1']))) ? htmlentities(addslashes(trim($_POST['phone1']))) : "";
	$phone2				= (isset($_POST['phone2']) && (!empty($_POST['phone2']))) ? htmlentities(addslashes(trim($_POST['phone2']))) : "";
	$phone3				= (isset($_POST['phone3']) && (!empty($_POST['phone3']))) ? htmlentities(addslashes(trim($_POST['phone3']))) : "";
	$cellphone			= (isset($_POST['cellphone']) && (!empty($_POST['cellphone']))) ? htmlentities(addslashes(trim($_POST['cellphone']))) : "";
	$fax1				= (isset($_POST['fax1']) && (!empty($_POST['fax1']))) ? htmlentities(addslashes(trim($_POST['fax1']))) : "";
	$fax2				= (isset($_POST['fax2']) && (!empty($_POST['fax2']))) ? htmlentities(addslashes(trim($_POST['fax2']))) : "";
	$email				= (isset($_POST['email']) && (!empty($_POST['email']))) ? htmlentities(addslashes(trim($_POST['email']))) : "";
	$paymentMode		= (isset($_POST['paymentMode']) && (!empty($_POST['paymentMode']))) ? htmlentities(addslashes(trim($_POST['paymentMode']))) : "";
	$medicalResult		= (isset($_POST['medicalResult']) && (!empty($_POST['medicalResult']))) ? htmlentities(addslashes(trim($_POST['medicalResult']))) : "";
	$terms				= (isset($_POST['terms']) && (!empty($_POST['terms']))) ? htmlentities(addslashes(trim($_POST['terms']))) : 0;
	$beginningBalance	= (isset($_POST['beginningBalance']) && (!empty($_POST['beginningBalance']))) ? htmlentities(addslashes(trim($_POST['beginningBalance']))) : 0.00;
	$nameInCertificate	= (isset($_POST['nameInCertificate']) && (!empty($_POST['nameInCertificate']))) ? htmlentities(addslashes(trim($_POST['nameInCertificate']))) : "yes";
	
	$dataArray			= $_POST;
	/**START OF VALIDATION**/
	/**Check Required Fields**/
	$required_fields   	= array('agencyCode','agencyName','address');
	if(empty($agencyCode) || empty($agencyName) || empty($address)){
		$errmsg[]		= $validation_class->validations($required_fields,'required');
		$continue_flag	= 0;
	}
	
	$existing_field['agencyCode']   	= $agencyCode;
	/**Check If Client Exists**/
	if(!empty($agencyCode) && $task == 'create'){
		$errmsg[]		= $validation_class->validations($existing_field,'exists',"",$table);
		if(!empty($errmsg) && is_null($errmsg)){
			$continue_flag	= 0;
		}
	}
	
	/**END OF VALIDATION**/
	
	if($continue_flag == 1){
		unset($dataArray['clientinfo_stat']);
		unset($dataArray['task']);
		
		$dataArray['updateBy']		= $username;
		$dataArray['updateDate']	= $datetime;
		$dataArray['updateProgram']	= ($task == 'create') ? "CSF-New" : "CSF-Edit";
		
		/**SAVE CHANGES**/
		if($task == 'create'){
			$dataArray['enteredBy']	= $username;
			$errmsg[] = $recon->insertRecord($dataArray, $table);
			
			if(!empty($errmsg) && is_null($errmsg)){
				$continue_flag = 2;
			}
		}else if($task == 'edit'){
			$errmsg[] = $recon->updateRecord($dataArray, $table, " agencyCode = '$agencyCode' ");
			if(!empty($errmsg) && is_null($errmsg)){
				$continue_flag = 2;
			}
		}
	}
	
	$error_messages = implode('', $errmsg);
	
	echo $continue_flag.'|'.$error_messages;
	ob_end_flush();
?>
