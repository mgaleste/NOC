<?php
	ob_start();
	session_start();
	include_once '../site.php';

	$recon 				= new recordUpdate();

	$errmsg				= array();
	$user				= (isset($_SESSION['gp_username'])) ? $_SESSION['gp_username'] : "";
	$datetime			= date("Y-m-d H:i:s");
	$agency_table		= "agency";
	$signatory_table	= "agency_signatories";
	$accountexec_table	= "signatories_account_executives";
	$destinations_table	= "signatories_destination";
	$package_table		= "signatory_package_rate";
	$special_table		= "special_instructions";
	
	$continue_flag		= 1;
	
	$agencyCode			= (isset($_POST['agencyCode']) && (!empty($_POST['agencyCode']))) ? htmlentities(addslashes(trim($_POST['agencyCode']))) : "";
	
	if(!empty($agencyCode)){
		/**DELETE OLD RECORDS**/
		$oldSignatoryArray	= $recon->retrieveEntry($signatory_table,array('agencySignatoryCode'),""," agencyCode = '".$agencyCode."' ");
		if(!empty($oldSignatoryArray)){
			$oldSignatoryList	= (!empty($oldSignatoryArray) && !is_null($oldSignatoryArray)) ? "'".implode("','",$oldSignatoryArray)."'" : "";
			
			$errmsg[]	= $recon->deleteRecord($package_table," signatoryCode IN($oldSignatoryList) ");
			$errmsg[]	= $recon->deleteRecord($destinations_table," agencySignatoryCode IN($oldSignatoryList) ");
			$errmsg[]	= $recon->deleteRecord($accountexec_table," agencySignatoryCode IN($oldSignatoryList) ");
			
			$errmsg[]	= $recon->deleteRecord($signatory_table," agencyCode = '".$agencyCode."' ");
		}
		
		$errmsg[]	= $recon->deleteRecord($special_table," agencyCode = '".$agencyCode."' ");
		$errmsg[]	= $recon->deleteRecord($agency_table," agencyCode = '".$agencyCode."' ");
		
		if(!empty($errmsg) && !is_null($errmsg)){
			$continue_flag = 0;
		}
	}	
	
	$error_messages = implode('', $errmsg);
	
	echo $continue_flag.'|'.$error_messages;

	ob_end_flush();
?>
