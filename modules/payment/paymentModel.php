<?	
	//Declaration of objects and other variables	  
		$userTable				=	"subscriptionpayment";
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

function updatePaymentStatus($sid,$dataChange){
    $recon = 	new recordUpdate();
    $coreMaintenance = new coreMaintenance();
    $core 					=	new coreFunctions();

    $retrieveData = $recon->retrieveCustomQuery("SELECT subscriptionPaymentId, amount, statusId FROM subscriptionpayment m WHERE m.subscriptionPaymentId='$sid'");
    $data = explode("|",$retrieveData[0]);

    if($data[2]<>$dataChange['status']){
        $siteStatusId 		=	$core->getIdCaption("subscriptionPaymentStatusId",'subscriptionpaymentstatus',"subscriptionPaymentId='$sid' and endBy=''");
        
        $siteStatArray['endBy']         =     $_SESSION['gp_username'];
        $siteStatArray['endDate']       =     date("Y-m-d H:i:s");
        //var_dump($siteStatArray);
        $queryResult 		= 	$coreMaintenance->saveDatabase('edit','subscriptionpaymentstatus',$siteStatArray,$siteStatusId);


        $newsiteStatArray['subscriptionPaymentStatusId'] 	=	getIDCustom('subscriptionpaymentstatus','subscriptionPaymentStatusId');
        $newsiteStatArray['subscriptionPaymentId']            =       $data[0];
        $newsiteStatArray['statusId']          =       $dataChange['statusId'];
        
        $newsiteStatArray['startBy']         =     $_SESSION['gp_username'];
        $newsiteStatArray['startDate']       =     date("Y-m-d H:i:s");
        //var_dump($newsiteStatArray);
        $queryResultSiteStat 		= 	$coreMaintenance->saveDatabase('create','subscriptionpaymentstatus',$newsiteStatArray);


    }
}

function updateSubscriptionStatus($sid,$dataChange){
    $recon = 	new recordUpdate();
    $coreMaintenance = new coreMaintenance();
    $core 					=	new coreFunctions();
    $retrieveData = $recon->retrieveCustomQuery("SELECT customerSubscriptionId, siteId, statusId FROM customersubscription m WHERE m.customerSubscriptionId='$sid'");
    $data = explode("|",$retrieveData[0]);
    
    if($data[2]<>$dataChange['status']){
        $siteStatusId 		=	$core->getIdCaption("subscriptionStatusId",'subscriptionstatus',"customerSubscriptionId='$sid' and endBy=''");
        $siteStatArray['endBy']         =     $_SESSION['gp_username'];
        $siteStatArray['endDate']       =     date("Y-m-d H:i:s");
        //var_dump($siteStatArray);
        $queryResult 		= 	$coreMaintenance->saveDatabase('edit','subscriptionstatus',$siteStatArray,$siteStatusId);


        $newsiteStatArray['subscriptionStatusId'] 	=	getIDCustom('subscriptionstatus','subscriptionStatusId');
        $newsiteStatArray['customerSubscriptionId']            =       $data[0];
        $newsiteStatArray['statusId']          =       $dataChange['statusId'];
        $newsiteStatArray['startBy']         =     $_SESSION['gp_username'];
        $newsiteStatArray['startDate']       =     date("Y-m-d H:i:s");
        //var_dump($newsiteStatArray);
        $queryResultSiteStat 		= 	$coreMaintenance->saveDatabase('create','subscriptionstatus',$newsiteStatArray);


    }
}

//-----------------------------------------UPDATING ----------------------------------------------------//
        if (((!empty($sid)) && $task == 'edit') || ((!empty($sid)) && $task == 'view')) {
		$retrieveDestination = $recon->retrieveCustomQuery("SELECT * FROM $userTable m where m.subscriptionPaymentId='$sid'");
		$applicantData = explode("|",$retrieveDestination[0]);
		$customerSubscriptionId = $applicantData[1];
		$amount = $applicantData[3];
		$dueDate =  $applicantData[4];
		
        } else if (((!empty($sid)) && $task == 'paid') ) {
            $retrieveDestination = $recon->retrieveCustomQuery("SELECT Concat(c.lastName ,',',c.firstName,' ',c.middleName,' | ', sp.siteName)
                ,m.customerSubscriptionId
                FROM subscriptionpayment m
                join customersubscription cs on m.customerSubscriptionId = cs.customerSubscriptionId
                join customer c on cs.customerId=c.customerId
                join site sp on cs.siteId=sp.siteId
                where m.subscriptionPaymentId='$sid'");
            $captions 	=	(empty($retrieveDestination)) ? $type : $retrieveDestination;
            
            $caption=explode("|",$captions[0]);
            var_dump($caption);
            $activity 	=	"$task Customer Payment ".$caption[0]."|".$caption[1];
            $core->logAdminTask($admin,$type,$task,$activity);
            $fieldArray = array ("statusId"=>"14");
            updateSubscriptionStatus($caption[2],$fieldArray);
            updatePaymentStatus($sid,$fieldArray);
            $users->updateRecord($fieldArray, $userTable, $userTable."Id='$sid'");
            header("location:index.php?mod=$mod&type=$type");
        } else if (((!empty($sid)) && $task == 'cancel') ) {
            $retrieveDestination = $recon->retrieveCustomQuery("SELECT Concat(c.lastName ,',',c.firstName,' ',c.middleName,' | ', sp.siteName)
                ,m.customerSubscriptionId
                FROM subscriptionpayment m
                join customersubscription cs on m.customerSubscriptionId = cs.customerSubscriptionId
                join customer c on cs.customerId=c.customerId
                join site sp on cs.siteId=sp.siteId
                where m.subscriptionPaymentId='$sid'");
            $captions 	=	(empty($retrieveDestination)) ? $type : $retrieveDestination;
           
            $caption=explode("|",$captions[0]);
           
            
            $activity 	=	"$task Customer Payment ".$caption[0];
            $core->logAdminTask($admin,$type,$task,$activity);
            $fieldArray = array ("statusId"=>"17");
            updatePaymentStatus($sid,$fieldArray);
            $users->updateRecord($fieldArray, $userTable, $userTable."Id='$sid'");
           header("location:index.php?mod=$mod&type=$type");
		
	} else if (((!empty($sid)) && $task == 'overdue') ) {
            $retrieveDestination = $recon->retrieveCustomQuery("SELECT Concat(c.lastName ,',',c.firstName,' ',c.middleName,' | ', sp.siteName)
                ,m.customerSubscriptionId
                FROM subscriptionpayment m
                join customersubscription cs on m.customerSubscriptionId = cs.customerSubscriptionId
                join customer c on cs.customerId=c.customerId
                join site sp on cs.siteId=sp.siteId
                where m.subscriptionPaymentId='$sid'");
            $captions 	=	(empty($retrieveDestination)) ? $type : $retrieveDestination;
          
            $caption=explode("|",$captions[0]);
            

            $activity 	=	"$task Customer Payment ".$caption[0];
            $core->logAdminTask($admin,$type,$task,$activity);
            $fieldArray = array ("statusId"=>"12");
            updateSubscriptionStatus($caption[2],$fieldArray);
            updatePaymentStatus($sid,$fieldArray);
            $users->updateRecord($fieldArray, $userTable, $userTable."Id='$sid'");
           header("location:index.php?mod=$mod&type=$type");

	}else {
		$amount = "";
		$dueDate = "";
		
	}
//-----------------------------------------SAVING ----------------------------------------------------//
if (isset($_POST['Save'])){
		$postArray 						=	$_POST;
		$usersArray 					=	array();
                
	if($task=='create'){
		
		$usersArray['subscriptionPaymentId'] 	=	getIDCustom($userTable,'subscriptionPaymentId');
		$usersArray['statusId']         =       $recon->GetValue('statusId','status','name="Pending" and type="payment"');
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
			$required_fields   	=	array('customerSubscriptionId', 'amount', 'dueDate');
			if(empty($customerSubscriptionId) || empty($amount) || empty($dueDate)){
					$errmsg[]	=	$validation_class->validations($required_fields,'required');
			}
		
		//Save Values to the Database if no Errors Found
		$error_messages				=	implode('', $errmsg);
               
		if (empty($error_messages)) {
			//Save Posted Array Values to Database
				$queryResult 		= 	$coreMaintenance->saveDatabase($task,$userTable,$usersArray,$sid);
				if($task=='create'){
                                    $siteStatArray = array();
                                    $siteStatArray['subscriptionPaymentStatusId'] 	=	getIDCustom('subscriptionpaymentstatus','subscriptionPaymentStatusId');
                                    $siteStatArray['subscriptionPaymentId']            =       $usersArray['subscriptionPaymentId'];
                                    $siteStatArray['statusId']          =       $usersArray['statusId'];
                                    $siteStatArray['startBy'] 		=	$admin;
                                    $siteStatArray['startDate'] 	=	$datetime;
                                    $queryResultSiteStat 		= 	$coreMaintenance->saveDatabase('create','subscriptionpaymentstatus',$siteStatArray,$sid);


                                    $paymentArray['statusId'] = $usersArray['statusId'];
                                    updateSubscriptionStatus($customerSubscriptionId,$paymentArray);
                                }
				if (empty($queryResult)) {
                                                $retrieveDestination = $recon->retrieveCustomQuery("SELECT Concat(c.lastName ,',',c.firstName,' ',c.middleName,' | ', sp.serviceProviderName) FROM subscriptionpayment m
                                                    join customersubscription cs on m.customerSubscriptionId = cs.customerSubscriptionId
                                                    join customer c on cs.customerId=c.customerId
                                                    join serviceprovider sp on cs.serviceProviderId=sp.serviceProviderId
                                                    where cs.customerSubscriptionId='$customerSubscriptionId'");
						$caption 	=	(empty($retrieveDestination)) ? $type : $retrieveDestination;
                                                
						$activity 	=	"$task Customer Payment ".$caption[0];
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
   $paginate_arr['addquery'] 	= "  and sp.siteName LIKE '%$psearch%' || c.firstName LIKE '%$psearch%' || c.lastName LIKE '%$psearch%' ";
}

$paginate_arr['paginatequery'] 	= "SELECT m.subscriptionPaymentId,c.firstName, c.middleName, c.lastName, sp.siteName, m.dueDate dueDate, s.name spStatus FROM $userTable m
join customersubscription cs on m.customerSubscriptionId = cs.customerSubscriptionId
join customer c on cs.customerId=c.customerId
join site sp on cs.siteId=sp.siteId
join status s on m.statusId=s.statusId
WHERE m.statusId <> '-1' ";
//$paginate_arr['paginatequery'] 	= "join customer_subscription cs using (customerId) join status s using (statusId)";

$paginate_arr['query'] 			= "ORDER BY dueDate ASC";

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
                        $fieldArray = array ("statusId"=>"17");
                        $users->updateRecord($fieldArray, $userTable, $userTable."Id='$did'");
			$retrieveDestination = $recon->retrieveCustomQuery("SELECT Concat(c.lastName ,',',c.firstName,' ',c.middleName,' | ', sp.serviceProviderName) FROM subscriptionpayment m
                        join customersubscription cs on m.customerSubscriptionId = cs.customerSubscriptionId
                        join customer c on cs.customerId=c.customerId
                        join serviceprovider sp on cs.serviceProviderId=sp.serviceProviderId
                        where cs.customerSubscriptionId='$did'");
                         $caption 	=	(empty($retrieveDestination)) ? $type : $retrieveDestination;
                      
                    $activity 	=	"Cancelled Customer Payment ".$caption[0];
			$core->logAdminTask($admin,$type,$retrieveDestination,$activity);
		}
		
        //delete selected
        //$users->deleteRecord($userTable, "customerId IN ($str_ids)");
        header("location:index.php?mod=$mod&type=$type");
    }
}

if ($task == 'delete') {
	$users->updateRecord($fieldArray, $userTable, $userTable."Id='$did'");
        $retrieveDestination = $recon->retrieveCustomQuery("SELECT Concat(c.lastName ,',',c.firstName,' ',c.middleName,' | ', sp.serviceProviderName) FROM subscriptionpayment m
        join customersubscription cs on m.customerSubscriptionId = cs.customerSubscriptionId
        join customer c on cs.customerId=c.customerId
        join serviceprovider sp on cs.serviceProviderId=sp.serviceProviderId
        where cs.customerSubscriptionId='$did'");
         $caption 	=	(empty($retrieveDestination)) ? $type : $retrieveDestination;
        
	$activity 	=	"Cancelled Customer Payment ".$caption[0];
	$core->logAdminTask($admin,$type,$task,$activity);
	$fieldArray = array ("statusId"=>"17");
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

