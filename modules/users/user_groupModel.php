<?php
 //Declaration of objects and other variables	  
	$userGroupTable			=	"groups";
	$usergroups				=	new users();		
	$userGroupsProd			=	new products();		
	$items 					=	(isset($_GET['items'])) ? $_GET['items'] : $userGroupsProd->defaultItemList();
	$userGroupsProd->setModule($mod);
	$userGroupsProd->setModuleType($type);										
	$userGroupsProd->setItems($items);		


	$recon 					=	new recordUpdate();
	$mform 					=	new formMaintenance();
	$core 					=	new coreFunctions();
	$coreMaintenance		=	new coreMaintenance();
	$validation_class 		=	new validations();	
	$modulerecord 			=	new record($userGroupTable);
	$sid 					=	(!empty($_GET['sid']))	? $_GET['sid']	: "";
	$type 					=	(!empty($_GET['type'])) ? $_GET['type'] : "";

	$errmsg 				=	array();
	$errmsg[] 				=	isset($_GET['errmsg']) ? $_GET['errmsg'] : "";
	$error_messages 		=	"";
	$required_fields		=	array();
	$alphanumeric_fields	=	array();
	$numeric_fields 		=	array();
	$admin					=	$_SESSION['gp_username'];
	$datetime				=	date("Y-m-d H:i:s"); 
	$currentdate			=	date("Y-m-d"); 
	$disabled 				= 	"";
	
	
	$isReadOnly				= ($task != 'view') ? "" : "readOnly" ;
	$ro_class				= ($task != 'view') ? "" : "ro_field" ;
	$taskView				= ($task != 'view') ? $task : "edit" ;	
	/*If Task is Edit or View
	 * Retrieve Usergroup's Informations
	 */
	if (((!empty($sid)) && $task == 'edit') || ((!empty($sid)) && $task == 'view')) { 	
			$arrayValues 			=	array('groupCode','groupName');
			$usergroups->setUserTable($userGroupTable);
			$usergroups->setUserId($sid);
			$usergroups->setUserArray($arrayValues);    
			$usersRetrieval 			=	$usergroups->userRetrieveInfo();					
			
		//Assigning Array Values to Variables	
			foreach($arrayValues as $aIndex => $aValue){ 
				$$aValue 				=	$usersRetrieval[$aIndex];
			}
		
		
		//group module access [ Module Access ]	
			$groupArrayValues 			=	array('groupCode','moduleCode');
			$usergroups->setUserCond("groupCode='$sid'");
			$usergroups->setUserTable("group_modules");
			$usergroups->setUserArray($groupArrayValues);    
			$usersGroupRetrieval 		=	$usergroups->userGroupAccessRetrieveInfo();								
			$userGroupModuleArray 		= 	array();
	 
		//Assigning Array Values to Variables [ Module Access ]	
			foreach($usersGroupRetrieval as $groupIndex => $groupValue){ 
						$moduleid 		=	$groupValue;
			}
			
	}else{
		//Initialize Variables 
			$initializeVariables		=	array('groupCode', 'groupName');	    
			foreach($initializeVariables as $initializeIndex => $initializeValue){ 
				$$initializeValue 		=	"";
			}
	}  
 
	//Insert and Update Records
	if(isset($_POST['Save'])){	
			$postArray 							=	$_POST;		
			$usersGroupArray 					=	array();			 
			$usersGroupAccessArray				=	array();
			$usersGroupAccessTaskArray				=	array();			 			 
			$groupId 							=	($task == 'create') ? getID($userGroupTable) : $sid;
		
			
		//Posting values and assigning posted values to variables.
			foreach($postArray as $postIndex => $postValue){					
				//Check if Posted Name is not Save Button
					if($postIndex!='Save'){
						if($postIndex!='modulelist' && $postIndex!='modulelistTask'){
							$usersGroupArray[$postIndex]		=  (isset($_POST[$postIndex])	&&	(!empty($_POST[$postIndex])))	?	htmlentities(trim($_POST[$postIndex]))	:	"";						
							$$postIndex  						=	$usersGroupArray[$postIndex];
						}else if($postIndex=='modulelist'){						
							$usersGroupAccessArray[$postIndex]	=  (isset($_POST[$postIndex])	&&	(!empty($_POST[$postIndex])))	?	$_POST[$postIndex]	:	"";						
							$$postIndex  						=	$usersGroupAccessArray[$postIndex];							 
						}else if($postIndex=='modulelistTask'){
							$usersGroupAccessTaskArray[$postIndex]	=  (isset($_POST[$postIndex])	&&	(!empty($_POST[$postIndex])))	?	$_POST[$postIndex]	:	"";						
							$$postIndex  						=	$usersGroupAccessArray[$postIndex];
						
						}						
					}			
			}
			
		if($task=='create'){	
			$usersGroupArray['id'] 				=	$groupId;			 	
					
			//$usersGroupArray[' entereddates']		= 	$datetime;
			$usersGroupArray['enteredBy'] 		=	$admin;
			$usersGroupArray['enteredDate'] 	=	$datetime;	
		}elseif($task=='edit'){	
			$usersGroupArray['updateBy'] 		=	$admin;
			$usersGroupArray['updateDate']		=	$datetime;
		}
			$error_messages = implode('', $errmsg);
			if (empty($error_messages)) {
				$queryResult 		= 	$coreMaintenance->saveDatabase($task,$userGroupTable,$usersGroupArray,$sid);
				if (empty($queryResult)) {
						if($task=='edit'){
							//Delete usergroups module access entries
							$usergroups->deleteRecord("group_modules_tasks", "groupCode='$sid'");
							$usergroups->deleteRecord("group_modules", "groupCode='$sid'");
						}
						
					//Saving Group Module Access Table
						foreach($modulelist as $modlistIndex => $modlistValue){
							$modulegroup['groupCode']	 	= $sid;
							$modulegroup['moduleCode'] 	= $modlistValue;
							$queryResult2 = $usergroups->insertRecord($modulegroup, "group_modules");
							$modulelistTaskLists = $usersGroupAccessTaskArray["modulelistTask"][$modlistValue];
							
					//saving Tasks
						foreach($modulelistTaskLists as $modlistTaskIndex => $modlistTaskValue){
								$modulegrouptask['groupCode']	 	= $sid;
								$modulegrouptask['moduleCode'] 	= $modlistValue;
								$modulegrouptask['taskCode'] 	= $modlistTaskValue;
								$queryResult2 = $usergroups->insertRecord($modulegrouptask, "group_modules_tasks");
							}
						
						}	
						
						//Redirection	
						if (empty($queryResult2)) {	

							$caption 	=	(empty($groupname)) ? $type : $groupname;
							$activity 	=	"$task $caption";
							$core->logAdminTask($admin,$type,$task,$activity);
				
							header("location:index.php?mod=$mod&type=$type");
						}
				}
			}
	}
  
	
if(isset($_POST['hidden_selected'])){
	
	if(count($_POST['hidden_selected'])>0){
		$str_ids 		=	implode(',',$_POST['delAnn']);			
		$delArr 		= 	$_POST['delAnn'];
		foreach($delArr as $did){
			$task		= 	"delete";
			$title 		=	$core->getIdCaption("groupname","usersgroup","id='$did'");
			$caption 	= 	(empty($title)) ? $type : $title;
			$activity 	=	"$task $caption";
			$core->logAdminTask($admin,$type,$task,$activity);
		}
		
		//delete selected
        $usergroups->deleteRecord("usersgroup", "id IN ($str_ids)");
        $usergroups->deleteRecord("groupmoduleaccess", "groupid IN ($str_ids)");		
	}
}else if($task=='delete'){	 
		$str_ids 	=	$_GET['sid'];		
		$title 		=	$core->getIdCaption("groupname","usersgroup","id='$str_ids'");
		$caption 	= 	(empty($title)) ? $type : $title;
		$activity 	=	"$task $caption";
		$core->logAdminTask($admin,$type,$task,$activity);
		
		$usergroups->deleteRecord("usersgroup", "id='$str_ids'");		
		$usergroups->deleteRecord("groupmoduleaccess", "groupid='$str_ids'");		
		 
		header("location:index.php?mod=$mod&type=$type");
	 
}
 
 
$search		= "";
$sascdesc	= "ASC";
$surl		= "";

  

$paginate_arr['addquery'] = "";
//SEARCH  
	if (isset($_POST['psearch_entry'])) {
		$parse_arr 					=	array('pg', 'search');
		$searchloc 					=	(string) URL_Parser($_SERVER['QUERY_STRING'], $parse_arr) . "&search=" . $_POST['psearch_entry'];
		header("Location:?" . $searchloc);
	}
	if (isset($_GET['search'])) {
		$psearch 					=	trim($_GET['search']);
		$searchArray 				=	array("groupname", "description");
		$userGroupsProd->setSearchVal($psearch);
		$userGroupsProd->setSearchArray($searchArray);
		$searchresult				=	$userGroupsProd->searchValue();
		$paginate_arr['addquery'] 	= 	$searchresult;
	}
	
$paginate_arr['paginatequery'] 		= 	"SELECT groupName, groupCode, systemStatus  FROM groups WHERE groupName != '' ";
$paginate_arr['query'] 				= 	"  ORDER BY groupCode ";
  
//call the function  where you will pass your array of queries for the class' future use
$modulerecord->manage_record_queries($paginate_arr);
//get pagination results then assign to $article list
$list = $modulerecord->get_paginated_array($items);
$result = $list['result'];
$num_rows = $list['num_rows'];
$PAGINATION_LINKS = $list['PAGINATION_LINKS'];
$PAGINATION_INFO = $list['PAGINATION_INFO'];
$PAGINATION_TOTALRECS = $list['PAGINATION_TOTALRECS'];


?>
