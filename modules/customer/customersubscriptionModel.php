<?	
	//Declaration of objects and other variables	  
		$userTable				=	"customersubscription";
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

    $retrieveData = $recon->retrieveCustomQuery("SELECT customerSubscriptionId, siteId, statusId FROM customersubscription m WHERE m.customerSubscriptionId='$sid'");
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
if (((!empty($sid)) && $task == 'viewsubscription')) {
		$retrieveDestination = $recon->retrieveCustomQuery("SELECT customerId, siteId FROM $userTable m where m.customerSubscriptionId='$sid'");
		$applicantData = explode("|",$retrieveDestination[0]);
		
		$customerId = $applicantData[0];
		$serviceProviderId = $applicantData[1];
	

		
	} else {
		$customerId= "";
		$serviceProviderId = "";
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
                $task = $_POST['task'];
	if($task=='create'){
		
		$usersArray['customerSubscriptionId'] 	=	getIDCustom($userTable,'customerSubscriptionId');
		$usersArray['statusId']         =       $recon->GetValue('statusId','status','name="Subscribed" and type="customer"');
		$usersArray2['statusId']        =   $usersArray['statusId'];

                $usersArray3['subscriptionStatusId'] 	=	getIDCustom('subscriptionstatus','subscriptionStatusId');
                $usersArray3['customerSubscriptionId'] = $usersArray['customerSubscriptionId'];
                $usersArray3['statusId'] = $usersArray['statusId'] ;
                $usersArray3['startBy'] 		=	$admin;
                $usersArray3['startDate'] 	=	$datetime;
	}elseif($task=='edit'){
		
		
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
			$required_fields   	=	array('customerId','siteId','spped');
			if(empty($customerId) || empty($siteId) || empty($speed)){
					$errmsg[]	=	$validation_class->validations($required_fields,'required');
			}
                        $validationArray = array('customerId'=>$customerId,);
                        $errmsg[] = $validation_class->validations($validationArray,'exists','',$userTable);
		//Save Values to the Database if no Errors Found
		$error_messages				=	implode('', $errmsg);
               
		if (empty($error_messages)) {
			//Save Posted Array Values to Database
				$queryResult 		= 	$coreMaintenance->saveDatabase($task,$userTable,$usersArray,$sid);
				$queryResult2 		= 	$coreMaintenance->saveDatabase('edit','customer',$usersArray2,$customerId);
                                $queryResult3 		= 	$coreMaintenance->saveDatabase('create','subscriptionstatus',$usersArray3,$customerId);
				if (empty($queryResult) && empty($queryResult2)) {
                                                $username 		=	$core->getIdCaption("concat(lastName,',',firstName,' ',middleName)","customer","customerId='$customerId'");
                                                $serviceProviderId 		=	$core->getIdCaption("siteName","site","siteId='$siteId'");
						$caption 	=	(empty($username)) ? $type : $username;
						$activity 	=	"$task Customer Subscription $username | $serviceProviderId";
						$core->logAdminTask($admin,$type,$task,$activity);
                                                

						header("location:index.php?mod=$mod&type=customer");
				}
		}
}
//-----------------------------------------LISTING ---------------------------------------------------------------//




//DELETE FROM LIST
/*
if (isset($_POST['hidden_selected'])) {
    if (count($_POST['hidden_selected']) > 0) {
        $str_ids = implode(',', $_POST['delAnn']);
		
		$delArr 		= 	$_POST['delAnn'];
		foreach($delArr as $did){
			$task		= 	"delete";
                        $fieldArray = array ("statusId"=>"-1");
                        $users->updateRecord($fieldArray, $userTable, $userTable."Id='$did'");
			$title 		=	$core->getIdCaption("username",$userTable,"customerId='$did'");
			$caption 	= 	(empty($title)) ? $type : $title;
			$activity 	=	"$task $caption";
			$core->logAdminTask($admin,$type,$task,$activity);
		}
		
        //delete selected
        //$users->deleteRecord($userTable, "customerId IN ($str_ids)");
        header("location:index.php?mod=$mod&type=$type");
    }
}

if ($task == 'delete') {
	$title 		=	$core->getIdCaption("username",$userTable,"customerId='$sid'");
	$caption 	= 	(empty($title)) ? $type : $title;
	$activity 	=	"$task $caption";
	$core->logAdminTask($admin,$type,$task,$activity);
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
}*/

?>

