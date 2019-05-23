<?	
	//Declaration of objects and other variables	  
		$userTable				=	"serviceprovidersites";
                $users 					=	new users();
		$userProd				=	new products();
		$items 					=	(isset($_GET['items'])) ? $_GET['items'] : $userProd->defaultItemList();
 		
		$mform 					=	new formMaintenance();
		$core 					=	new coreFunctions();
		$coreMaintenance		=	new coreMaintenance();
		$validation_class 		=	new validations();

                $recon 					= 	new recordUpdate();
		

		$admin					=	$_SESSION['gp_username'];
		$groupCode				=	$_SESSION['gp_group'];
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
//-----------------------------------------UPDATING ----------------------------------------------------//
if (((!empty($sid)) && $task == 'edit') || ((!empty($sid)) && $task == 'view')) {
		$retrieveDestination = $recon->retrieveCustomQuery("SELECT sp.serviceProviderId, sp.serviceProviderName, xCoordinate, yCoordinate, m.address, s.name spStatus FROM $userTable m
join status s using(statusId)
join serviceprovider sp using (serviceProviderId) WHERE m.serviceprovidersitesid='$sid'");
                
		$applicantData = explode("|",$retrieveDestination[0]);
		$serviceProviderId = $applicantData[0];
		$address = $applicantData[4];
		$xCoordinate =  $applicantData[2];
		$yCoordinate = $applicantData[3];
		
		
	

		
	} else {
		$serviceProviderId = "";
		$xCoordinate = "";
		$yCoordinate =  "";
		$address = "";
		
	}
//-----------------------------------------SAVING ----------------------------------------------------//
if (isset($_POST['Save'])){
		$postArray 						=	$_POST;
		$usersArray 					=	array();
                
	if($task=='create'){
		
		$usersArray['serviceprovidersitesid'] 	=	getIDCustom($userTable,'serviceprovidersitesid');
		$usersArray['statusId']         =       $recon->GetValue('statusId','status','name="New" and type="site"');
		$usersArray['enteredBy'] 		=	$admin;
		$usersArray['enteredDate'] 		=	$datetime;
		
	}elseif($task=='edit'){
		$usersArray['updateBy'] 		=	$admin;
		$usersArray['updateDate']		=	$datetime;
		
	}
		//Posting values and assigning posted values to variables.
                $arrayAvoid = array("task");
		$arrayTableTwo = array('');
		foreach($postArray as $postIndex => $postValue){
			//Check if Posted Name is not Save Button
				if($postIndex!='Save' && !in_array($postIndex,$arrayAvoid) && !in_array($postIndex,$arrayTableTwo)){
					$usersArray[$postIndex]	=  (isset($_POST[$postIndex])	&&	(!empty($_POST[$postIndex])))	?	htmlentities(trim($_POST[$postIndex]))	:	(($postIndex=='groupCode') ? 0 : "");
					$$postIndex  			=	$usersArray[$postIndex];
				}else if(in_array($postIndex,$arrayTableTwo) && !in_array($postIndex,$arrayAvoid)){
					$transactionsArray[$postIndex]	=  (isset($_POST[$postIndex])	&&	(!empty($_POST[$postIndex])))	?	htmlentities(trim($_POST[$postIndex]))	:	(($postIndex=='groupCode') ? 0 : "");
					$$postIndex  			=	$transactionsArray[$postIndex];
				}
		}
		//Validating Posted Values
			$required_fields   	=	array('serviceProviderId', 'address', 'xCoordinate', 'yCoordinate');
			if(empty($serviceProviderId) || empty($address) || empty($xCoordinate) || empty($yCoordinate)){
					$errmsg[]	=	$validation_class->validations($required_fields,'required');
			}
		
		//Save Values to the Database if no Errors Found
		$error_messages				=	implode('', $errmsg);
               
		if (empty($error_messages)) {
			//Save Posted Array Values to Database
				$queryResult 		= 	$coreMaintenance->saveDatabase($task,$userTable,$usersArray,$sid);
				
				if (empty($queryResult)) {
                                                $serviceProviderId 		=	$core->getIdCaption("serviceProviderName","serviceprovider","serviceProviderId='$serviceProviderId'");
						$caption 	=	(empty($address)) ? $type : $address;
						$activity 	=	"$task Service Provider Site $serviceProviderId $caption";
						$core->logAdminTask($admin,$type,$task,$activity);
                                                

						header("location:index.php?mod=$mod&type=$type");
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
   $paginate_arr['addquery'] 	= " and sp.serviceProviderName LIKE '%$psearch%' ";
}

$paginate_arr['paginatequery'] 	= "SELECT m.serviceprovidersitesId,sp.serviceProviderId, sp.serviceProviderName, xCoordinate, yCoordinate, m.address, s.name spStatus FROM $userTable m
join status s using(statusId)
join serviceprovider sp using (serviceProviderId) WHERE m.statusId <> '-1'";

//$paginate_arr['paginatequery'] 	= "join customer_subscription cs using (customerId) join status s using (statusId)";

$paginate_arr['query'] 			= "ORDER BY serviceProviderName ASC";

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
			$taskCBox		= 	"delete";
                        $fieldArray = array ("statusId"=>"-1");
                        $users->updateRecord($fieldArray, $userTable, $userTable."Id='$did'");

                        $retrieveDestination = $recon->retrieveCustomQuery("select concat(serviceProviderName,' ',m.address) from serviceprovidersites m join serviceprovider sp using(serviceProviderId) where serviceprovidersitesId='$did'");
			
			$activity 	=	"$taskCBox Service Provider Site ".$retrieveDestination[0];
			$core->logAdminTask($admin,$type,$taskCBox,$activity);
		}
		
        //delete selected
        //$users->deleteRecord($userTable, "customerId IN ($str_ids)");
        header("location:index.php?mod=$mod&type=$type");
    }
}

if ($task == 'delete') {
	$title 		=	$core->getIdCaption("username",$userTable,"serviceproviderId='$sid'");
	$caption 	= 	(empty($title)) ? $type : $title;
	$retrieveDestination = $recon->retrieveCustomQuery("select concat(serviceProviderName,' ',m.address) from serviceprovidersites m join serviceprovider sp using(serviceProviderId) where serviceprovidersitesId='$sid'");

	$activity 	=	"$taskCBox Service Provider Site ".$retrieveDestination[0];
	$fieldArray = array ("statusId"=>"-1");
        $users->updateRecord($fieldArray, $userTable, $userTable."Id='$sid'");
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

