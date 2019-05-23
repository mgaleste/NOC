<?php
	ob_start();
	include_once '../site.php'; 
	include_once '../model/connectUpdate.inc'; 
	include_once '../model/recordUpdate.inc'; 

	$objRecord 			= new recordUpdate();
	$login				= isset($_POST['login'])  ?  $_POST['login'] : "";
	$user				= isset($_POST['user'])  ?  $_POST['user'] : ""; 
	$tablerow			= "";
	$dataArray			= $user;

	if(!empty($dataArray)){
		$updateArray	= array();
		$testArray		= array();
		foreach($dataArray as $dataIndex => $dataValue){
			$new_value = $login[$dataIndex];
			$updateArray[$dataValue] = $new_value;
			$testArray[] = " WHEN '$dataValue' THEN '$new_value' ";
		}
		
		$whenQuery	= implode(' ',$testArray);
		$userlist	= "'".implode("','",$dataArray)."'";
	}	
	
	$query 	=  "UPDATE users
				SET is_login = CASE username 
				$whenQuery
				END
				WHERE username IN($userlist)";
	
	echo $result	= $objRecord->customizedQuery($query);
	
	ob_end_flush();
?>
