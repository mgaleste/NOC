<?
$dbTable				=	"adminlogs";
$status 				= 	"";
$recon 					=	new recordUpdate();
$log 					=	new products();
$log->setModule($mod);
$log->setModuleType($type);
$items 					=	(isset($_GET['items'])) ? $_GET['items'] : $log->defaultItemList();
$log->setItems($items);		
$postingdate  = "";
$mform 					=	new formMaintenance();
$core					=	new coreMaintenance();
$coreFunctions			=	new coreFunctions();
$validation_class 		=	new validations();
$imgfunc 				=	new imageFunctions();
$admin					=	$_SESSION['gp_username'];
$datetime				=	date("Y-m-d H:i:s"); 
$currentdate 			= 	date('m/d/Y');
$currenttime 			= 	date('H:i:s');
$modulerecord 			=	new record($dbTable);
$sid 					=	(!empty($_GET['sid'])) ? $_GET['sid'] : "";
$export_excel                   =   (!empty($_GET['export']))? : 0;
//-----------------------------------------LISTING ---------------------------------------------------------------//
$paginate_arr['addquery'] 	= "";
//SEARCH  
	if (isset($_POST['psearch_entry'])) {
		$parse_arr 					=	array('pg', 'search');
		$searchloc 					=	(string) URL_Parser($_SERVER['QUERY_STRING'], $parse_arr) . "&search=" . $_POST['psearch_entry'];
		header("Location:?" . $searchloc);
	}
	if (isset($_GET['search'])) {
		$psearch 					=	trim($_GET['search']);
                switch($cat){
                case 'site':
                            $searchArray 				=	array("siteName");
                break;
                case 'payment':
                            $searchArray 				=	array("siteName","lastName","firstName","middleName");
                break;
                case 'tickets':
                            $searchArray 				=	array("problem","description","siteName","lastName","firstName","middleName");
                break;
                }
		$log->setSearchVal($psearch);
		$log->setSearchArray($searchArray);
		$searchresult				=	$log->searchValue();
		$paginate_arr['addquery'] 	= 	$searchresult;
	}
switch($cat){
    case 'site':
        $paginate_arr['paginatequery'] 	= "SELECT sitestatusid, s.siteName, m.startDate, m.endDate, st.name status, st.color color FROM sitestatus m join site s on m.siteid=s.siteid join status st on m.statusid=st.statusid  ";
        $paginate_arr['query'] 			= "ORDER BY m.startDate DESC";
    break;
    case 'payment':
            $paginate_arr['paginatequery'] 	= "SELECT m.subscriptionpaymentid, site.siteName, concat(c.lastName,',',c.firstName,' ',c.middleName) customerName, m.startDate, m.endDate, st.name status, st.color color,
            s.amount, s.duedate, cs.speed
            FROM subscriptionpaymentstatus m
            join subscriptionpayment s on m.subscriptionpaymentid=s.subscriptionpaymentid
            join status st on m.statusid=st.statusid
            join customersubscription cs on s.customersubscriptionid=cs.customersubscriptionid
            join customer c on cs.customerid=c.customerid
            join site site on cs.siteid=site.siteid ";
        $paginate_arr['query'] 			= "ORDER BY m.startDate DESC";
    break;
    case 'tickets':
        $paginate_arr['paginatequery'] 	= "SELECT m.ticketId, site.siteName, m.startDate, m.endDate, st.name status, st.color color
        ,s.problem
        FROM ticketstatus m join ticket s on m.ticketId=s.ticketId
            join status st on m.statusid=st.statusid
            left join customer c on s.customerid=c.customerid
            join site site on s.siteid=site.siteid ";
        $paginate_arr['query'] 			= "ORDER BY m.startDate DESC";
    break;
    case 'logs':
    default:
        $paginate_arr['paginatequery'] 	= "SELECT id, username, ip_address, browser, module, task, timestamps, activitydone FROM $dbTable WHERE id>0 ";
        $paginate_arr['query'] 			= "ORDER BY id DESC";
    break;
}

//call the function  where you will pass your array of queries for the class' future use
$modulerecord->manage_record_queries($paginate_arr);
//get pagination results then assign to $article list
$list 					=	$modulerecord->get_paginated_array($items);
$result 				=	$list['result'];
$num_rows 				=	$list['num_rows'];
$PAGINATION_LINKS 		=	$list['PAGINATION_LINKS'];
$PAGINATION_INFO 		=	$list['PAGINATION_INFO'];
$PAGINATION_TOTALRECS 	=	$list['PAGINATION_TOTALRECS'];
  
?>

