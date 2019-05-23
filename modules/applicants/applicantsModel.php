<?	
	//Declaration of objects and other variables	  
		$userTable				=	"applicant_local";
		$userProd				=	new products();
		$items 					=	(isset($_GET['items'])) ? $_GET['items'] : $userProd->defaultItemList();
 		
		$mform 					=	new formMaintenance();
		$core 					=	new coreFunctions();
		$coreMaintenance		=	new coreMaintenance();
		$validation_class 		=	new validations();		
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

$paginate_arr['paginatequery'] 	= "SELECT applicantCode, firstName, middleName, lastName, age, gender, m.applicantType applicantType, d.positionTitle positionTitle, m.systemStatus systemStatus FROM $userTable m JOIN transactions d USING(applicantCode)";
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

