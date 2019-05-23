<?	
	//Declaration of objects and other variables	  
		$userTable				=	"ticket";
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

function updateTicketStatus($sid,$dataChange){
    $recon = 	new recordUpdate();
    $coreMaintenance = new coreMaintenance();
    $core 					=	new coreFunctions();
    
    $retrieveData = $recon->retrieveCustomQuery("SELECT ticketId, problem, statusId FROM ticket m WHERE m.ticketId='$sid'");
    $data = explode("|",$retrieveData[0]);

    if($data[2]<>$dataChange['status']){
        $siteStatusId 		=	$core->getIdCaption("ticketStatusId",'ticketstatus',"ticketId='$sid' and endBy=''");
        $siteStatArray['resolution']         =     $dataChange['resolution'];
        $siteStatArray['endBy']         =     $_SESSION['gp_username'];
        $siteStatArray['endDate']       =     date("Y-m-d H:i:s");
        //var_dump($siteStatArray);
        $queryResult 		= 	$coreMaintenance->saveDatabase('edit','ticketstatus',$siteStatArray,$siteStatusId);


        $newsiteStatArray['ticketStatusId'] 	=	getIDCustom('ticketstatus','ticketStatusId');
        $newsiteStatArray['ticketId']            =       $data[0];
        $newsiteStatArray['statusId']          =       $dataChange['statusId'];
        $newsiteStatArray['resolution']          =       $dataChange['resolution'];
        $newsiteStatArray['startBy']         =     $_SESSION['gp_username'];
        $newsiteStatArray['startDate']       =     date("Y-m-d H:i:s");
        //var_dump($newsiteStatArray);
        $queryResultSiteStat 		= 	$coreMaintenance->saveDatabase('create','ticketstatus',$newsiteStatArray);

        
    }
}

function updateSiteStatus($sid,$dataChange){
    $recon = 	new recordUpdate();
    $coreMaintenance = new coreMaintenance();
    $core 					=	new coreFunctions();

    $retrieveData = $recon->retrieveCustomQuery("SELECT siteId, siteName, statusId FROM site m WHERE m.siteId='$sid'");
    $data = explode("|",$retrieveData[0]);

    if($data[2]<>$dataChange['status']){
        $siteStatusId 		=	$core->getIdCaption("siteStatusId",'sitestatus',"siteId='$sid' and endBy=''");

        $siteStatArray['endBy']         =     $_SESSION['gp_username'];
        $siteStatArray['endDate']       =     date("Y-m-d H:i:s");
        //var_dump($siteStatArray);
        $queryResult 		= 	$coreMaintenance->saveDatabase('edit','sitestatus',$siteStatArray,$siteStatusId);


        $newsiteStatArray['siteStatusId'] 	=	getIDCustom('sitestatus','siteStatusId');
        $newsiteStatArray['siteId']            =       $data[0];
        $newsiteStatArray['statusId']          =       $dataChange['statusId'];
        $newsiteStatArray['startBy']         =     $_SESSION['gp_username'];
        $newsiteStatArray['startDate']       =     date("Y-m-d H:i:s");
        //var_dump($newsiteStatArray);
        $queryResultSiteStat 		= 	$coreMaintenance->saveDatabase('create','sitestatus',$newsiteStatArray);


    }
}
//-----------------------------------------UPDATING ----------------------------------------------------//
if (((!empty($sid)) && $task == 'edit') || ((!empty($sid)) && $task == 'view') || ((!empty($sid)) && $task == 'changestatus')) {
		$retrieveDestination = $recon->retrieveCustomQuery("SELECT ticketId, problem, description,resolution,siteId,customerId,m.statusId, s.name spStatus FROM $userTable m
join status s using(statusId) where m.ticketId='$sid'");
		$applicantData = explode("|",$retrieveDestination[0]);
		$ticketId = $applicantData[0];
		$problem = $applicantData[1];
                $description = $applicantData[2];
		$resolution =  $applicantData[3];
		$customerId = $applicantData[5];
		$statusId = $applicantData[6];
		$siteId = $applicantData[4];
		
                $isReadOnly = 'readonly';

		
	} else {
		$ticketId = "";
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
		
		
		$usersArray['statusId']         =       $recon->GetValue('statusId','status','name="New" and type="ticket"');
		$usersArray['startBy'] 		=	$admin;
		$usersArray['startDate'] 		=	$datetime;
		
	}elseif($task=='edit' || $task=='changestatus'){
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
			$required_fields   	=	array('problem', 'description', 'siteId');
			if(empty($problem) || empty($siteId) || empty($description)){
                            
					$errmsg[]	=	$validation_class->validations($required_fields,'required');
			}

                        if($task=='changestatus' && (empty($resolution) || empty($statusId) )){

					$errmsg[]	=	$validation_class->validations($required_fields,'required');
			}
		//overwrite
                if($task=='create'){
                    $usersArray['ticketId'] 	=	getIDCustom($userTable,'ticketId');
                }
		//Save Values to the Database if no Errors Found
		$error_messages				=	implode('', $errmsg);
               
		if (empty($error_messages)) {
			//Save Posted Array Values to Database
				$queryResult 		= 	$coreMaintenance->saveDatabase($task,$userTable,$usersArray,$sid);
				if($task=='create'){
                                    $siteStatArray = array();
                                    $siteStatArray['ticketStatusId'] 	=	getIDCustom('ticketstatus','ticketStatusId');
                                    $siteStatArray['ticketId']            =       $usersArray['ticketId'];
                                    $siteStatArray['statusId']          =       $usersArray['statusId'];
                                    $siteStatArray['startBy'] 		=	$admin;
                                    $siteStatArray['startDate'] 	=	$datetime;
                                    $queryResultSiteStat 		= 	$coreMaintenance->saveDatabase('create','ticketstatus',$siteStatArray,$sid);
                                }
                                if($task=='changestatus') {
                                    $queryResult 		= 	$coreMaintenance->saveDatabase('edit',$userTable,$usersArray,$sid);
                                    updateTicketStatus($sid,$usersArray);
                                }
				if (empty($queryResult)) {
                                                if($task=='create'){
                                                    $siteChange['siteId']=$siteId;
                                                    $siteChange['statusId']= $recon->GetValue('statusId','status','name="Alert" and type="site"');
                                                    updateSiteStatus($siteId,$siteChange);
                                                    $coreMaintenance->saveDatabase('edit','site',$siteChange,$siteId);
                                                }
                                                $caption 	=	(empty($username)) ? $type : $username;
						$activity 	=	"$task Ticket $ticketId $problem";
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
   $paginate_arr['addquery'] 	= " and serviceProviderName LIKE '%$psearch%' ";
}

$paginate_arr['paginatequery'] 	= "SELECT ticketId, problem, description, s.name spStatus FROM $userTable m
join status s using(statusId)";
$paginate_arr['addquery'] 	= " WHERE statusId <> '-1' ";
//$paginate_arr['paginatequery'] 	= "join customer_subscription cs using (customerId) join status s using (statusId)";

$paginate_arr['query'] 			= "ORDER BY ticketId ASC";

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
                        $users->updateRecord($fieldArray, $userTable, "ticketId='$did'");
			$title 		=	$core->getIdCaption("concat(ticketId,' ',problem)",$userTable,"ticketId='$did'");
			$caption 	= 	(empty($title)) ? $type : $title;
			$activity 	=	"$taskCBox Ticket $caption";
			$core->logAdminTask($admin,$type,$taskCBox,$activity);
		}
		
        //delete selected
        //$users->deleteRecord($userTable, "customerId IN ($str_ids)");
        header("location:index.php?mod=$mod&type=$type");
    }
}

if ($task == 'delete') {
	$title 		=	$core->getIdCaption("concat(ticketId,' ',problem)",$userTable,"ticketId='$sid'");
	$caption 	= 	(empty($title)) ? $type : $title;
	$activity 	=	"$task Ticket $caption";
	$core->logAdminTask($admin,$type,$task,$activity);
	$fieldArray = array ("statusId"=>"-1");
        $users->updateRecord($fieldArray, $userTable, "ticketId='$sid'");
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

