<?	
	//Declaration of objects and other variables	  
		$userTable				=	"users";
		$users 					=	new users();	
		$userProd 				=	new products();	
		$items 					=	(isset($_GET['items'])) ? $_GET['items'] : $userProd->defaultItemList();
 		$userProd->setModule($mod);
		$userProd->setModuleType($type);										
		$userProd->setItems($items);		
		
		$recon					=	new recordUpdate();
		$mform 					=	new formMaintenance();
		$core 					=	new coreFunctions();
		$coreMaintenance		=	new coreMaintenance();
		$validation_class 		=	new validations();		
		$admin					=	$_SESSION['gp_username'];
		$datetime				=	date("Y-m-d H:i:s"); 
		$currentdate			=	date("Y-m-d"); 
		$modulerecord 			=	new record($userTable);
		$sid 					=	(!empty($_GET['sid'])) ? $_GET['sid'] : "";
		$required_fields		=	array();
		$alphanumeric_fields	=	array();
		$numeric_fields 		=	array();
		$errmsg 				=	array();
		$errmsg[] 				=	isset($_GET['errmsg']) ? $_GET['errmsg'] : "";
		$error_messages 		=	"";

		$isReadOnly				= ($task != 'view') ? "" : "disabled" ;
		$ro_class				= ($task != 'view') ? "" : "ro_field" ;
		$taskView			= ($task != 'view') ? $task : "edit" ;
	/*If Task is Edit or View
	 * Retrieve User's Informations
	 */
		 
	if (((!empty($sid)) && $task == 'edit') || ((!empty($sid)) && $task == 'view')) {		 
			$arrayValues 			=	array('username', 'groupCode', 'systemStatus', 'firstName' ,'middleName', 'lastName', 'password');	    
			$users->setUserTable($userTable);
			$users->setUserId($sid);
			$users->setUserArray($arrayValues);    
			$usersRetrieval 		=	$users->userRetrieveInfo();	
		//Assigning Array Values to Variables	
			foreach($arrayValues as $aIndex => $aValue){ 
				$$aValue 			=	$usersRetrieval[$aIndex];
			}
			$valueGot = $recon->retrieveCustomQuery("SELECT groupName FROM groups WHERE groupCode='$groupCode'");
			$valueTrue = explode("|",$valueGot[0]);
			$groupCode = $valueTrue[0];		 
	} else {
		//Initialize Variables 
			$initializeVariables	=	array('username', 'groupCode', 'systemStatus', 'firstName' ,'middleName', 'lastName', 'password');	    
			foreach($initializeVariables as $initializeIndex => $initializeValue){ 
				$$initializeValue 	=	"";
			}		 
	}
	
 
	//Insert and Update Records
    if (isset($_POST['Save'])){
			$postArray 						=	$_POST;		
			$usersArray 					=	array();		
			$usersId 						=	($task == 'create')	? getID($userTable) : $sid;			 			
		if($task=='create'){	
			$usersArray['systemStatus'] 			=	"active";
			$genPass						=	generatePassword(12);
			$usersArray['password']		 	=	md5($genPass);			
			$usersArray['enteredby'] 		=	$admin;
			$usersArray['entereddate'] 		=	$datetime;			
			$usersArray['updateby'] 		=	$admin;
			$usersArray['updatedate']		=	$datetime;
		}elseif($task=='edit'){	
			$usersArray['updateby'] 		=	$admin;
			$usersArray['updatedate']		=	$datetime;
		}
			//Posting values and assigning posted values to variables.
			foreach($postArray as $postIndex => $postValue){					
				//Check if Posted Name is not Save Button
					if($postIndex!='Save'){
						$usersArray[$postIndex]	=  (isset($_POST[$postIndex])	&&	(!empty($_POST[$postIndex])))	?	htmlentities(trim($_POST[$postIndex]))	:	(($postIndex=='groupCode') ? 0 : "");						
						$$postIndex  			=	$usersArray[$postIndex];
					}			
			}
			
			//Validating Posted Values
				$required_fields   	=	array('firstName','lastName','groupCode','username');
				if(($groupCode=="") || empty($firstName) || empty($lastName) || empty($username)){
						$errmsg[]	=	$validation_class->validations($required_fields,'required');						
				}else{															
					if($task=='create'){	//Validation for Duplications
						$errmsg[]	=	($core->checkExist($userTable, "username" , $username)) ? "Duplicate Username Found. " : "";				
					}				
				}				
			
			//Save Values to the Database if no Errors Found
			$error_messages				=	implode('', $errmsg);
			if (empty($error_messages)) {
				//Save Posted Array Values to Database
					$queryResult 		= 	$coreMaintenance->saveDatabase($task,$userTable,$usersArray,$sid);
					if (empty($queryResult)) {
							$users->setUserId($usersId);
							$users->setUserTable($userTable);								
							$usersArray['generatepassword']		=	$genPass;
							$pass		=	$genPass;
							//$users->setUserArray($usersArray);    
							//$users->userSendEmail($task);
							header("location:popups/pdfuser.php?user=$username&pass=$pass");
							$pdfOk = 1;
						 	
							$caption 	=	(empty($username)) ? $type : $username;
							$activity 	=	"$task $username";
							$core->logAdminTask($admin,$type,$task,$activity);
				
							
							//header("location:index.php?mod=$mod&type=$type");
					}
			} 
    } 

//-----------------------------------------LISTING ---------------------------------------------------------------//
//$items 						= (isset($_GET['items'])) ? $_GET['items'] : 10;
$paginate_arr['addquery'] 	= "";

if (isset($_POST['psearch_entry'])) {
		$parse_arr 			= array('pg', 'search');
		$searchloc 			= (string) URL_Parser($_SERVER['QUERY_STRING'], $parse_arr) . "&search=" . $_POST['psearch_entry'];
		header("Location:?" . $searchloc);
}

if (isset($_GET['search'])) {
   $psearch = trim($_GET['search']);
   $paginate_arr['addquery'] 	= " WHERE firstName LIKE '%$psearch%' || lastName LIKE '%$psearch%' || userName LIKE '%$psearch%'";
}

$paginate_arr['paginatequery'] 	= "SELECT * FROM $userTable ";
$paginate_arr['query'] 			= "ORDER BY username";

//call the function  where you will pass your array of queries for the class' future use
$modulerecord->manage_record_queries($paginate_arr);
//get pagination results then assign to $article list
$list 					= $modulerecord->get_paginated_array($items);
$result 				= $list['result'];
$num_rows 				= $list['num_rows'];
$PAGINATION_LINKS 		= $list['PAGINATION_LINKS'];
$PAGINATION_INFO 		= $list['PAGINATION_INFO'];
$PAGINATION_TOTALRECS 	= $list['PAGINATION_TOTALRECS'];



//DELETE FROM LIST
if (isset($_POST['hidden_selected'])) {
    if (count($_POST['hidden_selected']) > 0) {
        $str_ids = implode(',', $_POST['delAnn']);
		
		$delArr 		= 	$_POST['delAnn'];
		foreach($delArr as $did){
			$task		= 	"delete";
			$title 		=	$core->getIdCaption("username",$userTable,"id='$did'");
			$caption 	= 	(empty($title)) ? $type : $title;
			$activity 	=	"$task $caption";
			$core->logAdminTask($admin,$type,$task,$activity);
		}
		
        //delete selected
        $users->deleteRecord($userTable, "id IN ($str_ids)");
        header("location:index.php?mod=$mod&type=$type");
    }
}

if ($task == 'delete') {
	$title 		=	$core->getIdCaption("username",$userTable,"id='$sid'");
	$caption 	= 	(empty($title)) ? $type : $title;
	$activity 	=	"$task $caption";
	$core->logAdminTask($admin,$type,$task,$activity);
			
    $users->deleteRecord($userTable, "id='$sid'");
    header("location:index.php?mod=$mod&type=$type");
}

if($task=='reset'){
	$title 		=	$core->getIdCaption("username",$userTable,"id='$sid'");
	$caption 	= 	(empty($title)) ? $type : $title;
	$activity 	=	"$task $caption";
	$core->logAdminTask($admin,$type,$task,$activity);

	$users->resetPassword($sid);	
	$errmsg = "Successfully Reset Password.";	
	header("location:index.php?mod=$mod&type=$type&errmsg=$errmsg");	
}

?>

