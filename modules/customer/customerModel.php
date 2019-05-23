<?	
	//Declaration of objects and other variables	  
		$userTable				=	"customer";
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

function updateSubscriptionStatus($sid,$dataChange){
    $recon = 	new recordUpdate();
    $coreMaintenance = new coreMaintenance();
    $core 					=	new coreFunctions();

    $retrieveData = $recon->retrieveCustomQuery("SELECT customerId, siteId, statusId FROM customersubscription m WHERE m.customerSubscriptionId='$sid'");
    $data = explode("|",$retrieveData[0]);

    if($data[2]<>$dataChange['status']){
        $siteStatusId 		=	$core->getIdCaption("subscriptionStatusId",'subscriptionstatus',"subscriptionstatusId='$sid' and endBy=''");
        $siteStatArray['endBy']         =     $_SESSION['gp_username'];
        $siteStatArray['endDate']       =     date("Y-m-d H:i:s");
        //var_dump($siteStatArray);
        $queryResult 		= 	$coreMaintenance->saveDatabase('edit','subscriptionstatus',$siteStatArray,$siteStatusId);


        $newsiteStatArray['subscriptionstatusId'] 	=	getIDCustom('subscriptionstatus','subscriptionstatusId');
        $newsiteStatArray['ticketId']            =       $data[0];
        $newsiteStatArray['statusId']          =       $dataChange['statusId'];
        $newsiteStatArray['startBy']         =     $_SESSION['gp_username'];
        $newsiteStatArray['startDate']       =     date("Y-m-d H:i:s");
        //var_dump($newsiteStatArray);
        $queryResultSiteStat 		= 	$coreMaintenance->saveDatabase('create','subscriptionstatus',$newsiteStatArray);


    }
}
//-----------------------------------------UPDATING ----------------------------------------------------//
if (((!empty($sid)) && $task == 'edit') || ((!empty($sid)) && $task == 'view')) {
		$retrieveDestination = $recon->retrieveCustomQuery("SELECT customerId, username, firstName, middleName, lastName, address, contactNumber, email, s.name FROM $userTable m join status s using (statusId) where m.customerId='$sid'");
		$applicantData = explode("|",$retrieveDestination[0]);
		$firstName = $applicantData[2];
		$middleName = $applicantData[3];
		$lastName =  $applicantData[4];
		$email = $applicantData[7];
		$address = $applicantData[5];
		$contactNumber = $applicantData[6];
		$username = $applicantData[1];
	

		
	} else {
		$firstName = "";
		$middleName = "";
		$lastName =  "";
		$email = "";
		$contactNumber = "";
		$address = "";
		$username = "";
	}
//-----------------------------------------SAVING ----------------------------------------------------//
if (isset($_POST['Save'])){
		$postArray 						=	$_POST;
		$usersArray 					=	array();
                
	if($task=='create'){
		
		$usersArray['customerId'] 	=	getIDCustom($userTable,'customerId');
		$usersArray['statusId']         =       $recon->GetValue('statusId','status','name="New" and type="customer"');
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
			$required_fields   	=	array('firstName','lastName','username','address','email','contactNumber');
			if(empty($firstName) || empty($lastName) || empty($username) || empty($address) || empty($email) || empty($contactNumber)){
					$errmsg[]	=	$validation_class->validations($required_fields,'required');
			}
                        if($task=='create'){	//Validation for Duplications
                                $errmsg[]	=	($core->checkExist($userTable, "username" , $username)) ? "Duplicate Username Found. " : "";
                        }
		
		//Save Values to the Database if no Errors Found
		$error_messages				=	implode('', $errmsg);
               
		if (empty($error_messages)) {
			//Save Posted Array Values to Database
				$queryResult 		= 	$coreMaintenance->saveDatabase($task,$userTable,$usersArray,$sid);
				$usersArray2['username'] 			=	$username;
                                $usersArray2['firstName'] 			=	$firstName;
                                $usersArray2['middleName'] 			=	$middleName;
                                $usersArray2['lastName']		 	=	$lastName;
                                $usersArray2['groupCode'] 		=	"customer";
                                $usersArray2['entereddate'] 		=	$datetime;
                                $usersArray2['systemStatus'] 			=	"active";
                                $genPass						=	generatePassword(12);
                                $usersArray2['password']		 	=	md5($genPass);
                                $usersArray2['enteredby'] 		=	$admin;
                                $usersArray2['entereddate'] 		=	$datetime;
                                $queryResult2 		= 	$coreMaintenance->saveDatabase("create","users",$usersArray2,$sid);
				if (empty($queryResult)) {
						$caption 	=	(empty($username)) ? $type : $username;
						$activity 	=	"$task Customer $lastName, $firstName $middleName";
						$core->logAdminTask($admin,$type,$task,$activity);
                                                $isReadOnly = 'disabled';
                                                $error_messages	= 'Saved New Customer';
                                                header("location:popups/pdfcustomer.php?user=$username&pass=$genPass");

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
   $paginate_arr['addquery'] 	= " and firstName LIKE '%$psearch%' || lastName LIKE '%$psearch%' || username LIKE '%$psearch%'";
}

$paginate_arr['paginatequery'] 	= "SELECT customerId, username, firstName, middleName, lastName, address, contactNumber, email, s.name customerStatus FROM $userTable m
join status s using(statusId) WHERE s.statusId <> '-1'";

//$paginate_arr['paginatequery'] 	= "join customer_subscription cs using (customerId) join status s using (statusId)";

$paginate_arr['query'] 			= "ORDER BY lastName ASC, firstName ASC";

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
			$firstName 		=	$core->getIdCaption("firstName",$userTable,"customerId='$did'");
                        $middleName 		=	$core->getIdCaption("middleName",$userTable,"customerId='$did'");
                        $lastName 		=	$core->getIdCaption("lastName",$userTable,"customerId='$did'");
			$caption 	= 	(empty($firstName )) ? $type : $lastName.', '.$firstName .' '.$middleName;
			$activity 	=	"$taskCBox Customer $caption";
			$core->logAdminTask($admin,$type,$taskCBox,$activity);
		}
		
        //delete selected
        //$users->deleteRecord($userTable, "customerId IN ($str_ids)");
       header("location:index.php?mod=$mod&type=$type");
    }
}

if ($task == 'delete') {
	$fieldArray = array ("statusId"=>"-1");
        $users->updateRecord($fieldArray, $userTable, $userTable."Id='$sid'");
        $firstName 		=	$core->getIdCaption("firstName",$userTable,"customerId='$sid'");
        $middleName 		=	$core->getIdCaption("middleName",$userTable,"customerId='$sid'");
        $lastName 		=	$core->getIdCaption("lastName",$userTable,"customerId='$sid'");
        $caption 	= 	(empty($firstName )) ? $type : $lastName.', '.$firstName .' '.$middleName;
        $activity 	=	"$task Customer $caption";
        $core->logAdminTask($admin,$type,$task,$activity);
	
    header("location:index.php?mod=$mod&type=$type");
}

?>

