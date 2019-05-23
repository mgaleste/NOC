<?php
	ob_start();
	session_start();
	include_once '../site.php';

	$recon 		= new recordUpdate();
	$fields		= isset($_POST['fields'])  ?  htmlentities($_POST['fields'])  : ""; 
	$table		= isset($_POST['table'])  ?  htmlentities($_POST['table'])  : ""; 
	$condition 	= isset($_POST['condition'])  ? $_POST['condition'] : "";  
	$orderby 	= isset($_POST['orderby'])  ?  htmlentities($_POST['orderby'])  : "";  

	$retrieveValues = "";
	$returnVal = "";
	if(strpos($fields,",")){
		$fields2 	= explode(',',$fields);
		$orderby 	= (!empty($orderby)) ? " ORDER BY ".$orderby : "";
		$retrievedArray	= $recon->retrieveEntry($table,$fields2,"",stripslashes($condition) . $orderby); 
		if(!empty($retrievedArray)){
			foreach($retrievedArray as $retrievedIndex => $retrievedValue){
				$$retrievedIndex 	= $retrievedValue;
				$returnVal 			.= html_entity_decode($retrievedValue).'|';
			}
		}else{
			$returnVal .= '|';
		}			
	}else{
		$retrieveValues = $recon->GetValue(stripslashes($fields),stripslashes($table),stripslashes($condition),stripslashes($orderby));	
		if(!empty($retrieveValues)){
			$returnVal .= html_entity_decode($retrieveValues).'|';
		}else{
			$returnVal .= '|';
		}
	}
	echo $returnVal;

	ob_end_flush();
?>
