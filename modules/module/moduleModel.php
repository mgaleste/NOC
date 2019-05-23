<?
$dbTable				=	"modules";
$refTable 				= 	"reference";
$recon 					= 	new recordUpdate();
$module					=	new products();
$items 					=	(isset($_GET['items'])) ? $_GET['items'] : $module->defaultItemList();
$subtask 				=	(isset($_GET['subtask'])) ? $_GET['subtask'] : "";
$type					=  	isset($_GET['type']) ? $_GET['type'] : "";
$module->setModule($mod);
$module->setModuleType($type);										
$module->setItems($items);	
   
$mform 					=	new formMaintenance();
$coreMaintenance		=	new coreMaintenance();
$core					=	new coreFunctions();
$validation_class 		=	new validations();
$imgfunc 				=	new imageFunctions();
$modulerecord 			=	new record($dbTable);
$admin					=	$_SESSION['gp_username'];
$datetime				=	date("Y-m-d H:i:s"); 
$currentdate 			= 	date('m/d/Y');
$currenttime 			= 	date('H:i:s');
$sid 					=	(!empty($_GET['sid'])) ? $_GET['sid'] : "";

$required_fields		=	array();
$alphanumeric_fields	=	array();
$numeric_fields 		=	array();
$errmsg 				=	array();
$errmsg[] 				=	isset($_GET['errmsg']) ? $_GET['errmsg'] : "";
$error_messages 		=	"";
$remarks1 				=	"";
$oldimagepath 			=	"";
$isDuplicate 			=	"";
  
if (((!empty($sid)) && $task == 'edit') || ((!empty($sid)) && $task == 'view')) {
	//Retrieve Values in modules
    $arrayValues = array('id', 'modulename', 'modulecaption', 'modulestat', 'menuorder', 'modulegroup', 'stat', 'type', 'is_category', 'is_postingdate', 'is_author', 'is_image', 'is_title', 'is_content', 'is_tag', 'imagepath');
    $retArray = $recon->retrieveEntry($dbTable, $arrayValues, "", "id='$sid'");
	foreach ($retArray as $retIndex => $retValue) {
		$$retIndex 		=	$retValue;
        $mainArr 		=	explode('|', $$retIndex);
        $modid 			=	$mainArr[0];
		$modulename		=	$mainArr[1];
		$modulecaption	=	$mainArr[2];
		$oldmodulecaption	=	$mainArr[2];
		$modulestat		=	$mainArr[3];
		$menuorder		=	$mainArr[4];
		$modulegroup	=	$mainArr[5];
		$stat			=	$mainArr[6];
		$modtype		=	$mainArr[7];
		$oldmodtype		=	$mainArr[7];
		$is_category	=	$mainArr[8];
		$is_postingdate	=	$mainArr[9];
		$is_author		=	$mainArr[10];
		$is_image		=	$mainArr[11];	
		$is_title		=	$mainArr[12];
		$is_content		=	$mainArr[13];
		$is_tag			=	$mainArr[14];
		$imagepath		=	$mainArr[15];	  
    }
	 
	//Retrieve Values in reference
	$imgRefDetails = array('max_main_height','max_main_width','max_thumb_height','max_thumb_width','max_kb');
	foreach($imgRefDetails as $imgRefDetailsIndex => $imgRefDetailsValue){				
			$arrayValues2 	= array('id', 'name', 'remarks1');
			$retArray2 		= $recon->retrieveEntry($refTable, $arrayValues2, "", "ref_name='$oldmodtype' AND name='$imgRefDetailsValue'");
			foreach ($retArray2 as $retIndex2 => $retValue2) {
				$$retIndex2 			=	$retValue2;
				$mainArr2 				=	explode('|', $$retIndex2);
				$modid2					=	$mainArr2[0];			 
				$$imgRefDetailsValue	=	$mainArr2[2];
			 }
	}	 
	
} else {
    //Initialize Variables	
	$initializeVariables	=	array('id', 'modulename', 'modulecaption', 'modulestat', 'menuorder', 'modulegroup', 'stat', 'is_category', 'is_postingdate', 'is_author', 'is_image', 'is_title', 'is_content', 'is_tag', 'imagepath');    
	foreach($initializeVariables as $initializeIndex => $initializeValue){
		$$initializeValue 	=	"";
	}
	
	$initializeVariables2['max_main_height']	=	500;
	$initializeVariables2['max_main_width']		=	500;
	$initializeVariables2['max_thumb_height']	=	300;
	$initializeVariables2['max_thumb_width']	=	300;
	$initializeVariables2['max_kb']				=	5000;
	
	foreach($initializeVariables2 as $initializeIndex2 => $initializeValue2){
		$$initializeIndex2 		=	$initializeValue2;
	}    
}

    //Insert and Update Records
    if (isset($_POST['save'])){
        
        $moduleid 						= 	($task == 'create') ? getID($dbTable) 	: $sid;
        $moduleArr['id'] 				= 	$moduleid;
        $moduleArr['menuorder']			= 	$moduleid;		
		$moduleArr['modulecaption'] 	= 	(isset($_POST['modulecaption']) 	&& (!empty($_POST['modulecaption']))) ? htmlentities(trim($_POST['modulecaption'])) : "";
		$moduleArr['modulename'] 		= 	(isset($_POST['modulename']) 	&& (!empty($_POST['modulename']))) ? htmlentities(trim($_POST['modulename'])) : "";			 
		
		$moduleArr['modulestat']		=	 "back";
		$moduleArr['remarks']			=	 str_replace(" ","_",strtolower($moduleArr['modulecaption']));
		$moduleArr['type']				=	 str_replace(" ","_",strtolower($moduleArr['modulecaption']));
		$moduleArr['modulegroup']		=	 "Modules";
		$moduleArr['stat']				=	 "active";
 			
			$modulecaption 				= 	 $moduleArr['modulecaption'];
			$modulename 				= 	 $moduleArr['modulename'];
			$modtype					=	 $moduleArr['type'];
			//Validating Posted Values
			$required_fields   			=	array('modulecaption','modulename');
			if(empty($modulecaption) || empty($modulename)){					 
				$errmsg[]		=	$validation_class->validations($required_fields,'required');
			}
				
		$isDuplicate = $core->checkDuplicateEntry($dbTable,"modulecaption='$modulecaption'");
			if($isDuplicate===true){
				if($task=='edit'){
					$errmsg[] =	($modulecaption!=$oldmodulecaption) ? "Duplicate Entry Found." : "";
				}else{
					$errmsg[] =	"Duplicate Entry Found.";
				}
			}
			
			
        	$error_messages			=	implode('', $errmsg);
			if (empty($error_messages)) {
				$queryResult 		= 	$coreMaintenance->saveDatabase($task,$dbTable,$moduleArr,$sid);
				if (empty($queryResult)) {
						$caption = (empty($modulecaption)) ? $type : $modulecaption;
						$activity 	=	"$task $caption";
						$core->logAdminTask($admin,$type,$task,$activity);
						
						/************************CREATE UPLOADS FOLDER******************************************/
						
					if($modulename!='downloads'){	
						if (!file_exists("../uploads/$modtype")) {
							mkdir("../uploads/$modtype", 0777);
						}
						
						if (!file_exists("../uploads/$modtype/large")) {
							mkdir("../uploads/$modtype/large", 0777);
						}
						
						if (!file_exists("../uploads/$modtype/resize")) {
							mkdir("../uploads/$modtype/resize", 0777);
						}
						
						if (!file_exists("../uploads/$modtype/thumb")) {
							mkdir("../uploads/$modtype/thumb", 0777);
						}
					}else{
						if (!file_exists("../uploads/$modtype")) {
							mkdir("../uploads/$modtype", 0777);
						}	
					}	
						
						$referenceArr['max_main_height']	= 	(isset($_POST['max_main_height']) 	&& (!empty($_POST['max_main_height']))) 	? $_POST['max_main_height'] 	: 500;
						$referenceArr['max_main_width']		= 	(isset($_POST['max_main_width']) 	&& (!empty($_POST['max_main_width']))) 		? $_POST['max_main_width'] 		: 500;
						$referenceArr['max_thumb_height']	= 	(isset($_POST['max_thumb_height']) 	&& (!empty($_POST['max_thumb_height']))) 	? $_POST['max_thumb_height'] 	: 300;
						$referenceArr['max_thumb_width']	= 	(isset($_POST['max_thumb_width']) 	&& (!empty($_POST['max_thumb_width']))) 	? $_POST['max_thumb_width'] 	: 300;
						$referenceArr['path_main']			=   "../uploads/$modtype/large/";
						$referenceArr['path_thumb']			=   "../uploads/$modtype/thumb/";
						$referenceArr['max_kb']				= 	(isset($_POST['max_kb']) 	&& (!empty($_POST['max_kb']))) 	? $_POST['max_kb'] 	: 5000;
						 
						 
						
						foreach($referenceArr as $refIndex => $refValue){
							
							if($task=='create')
								$refDetailArr['id']				= 	getID($refTable);								
								$refDetailArr['ref_name']		=   $modtype;
								$refDetailArr['name']			= 	$refIndex;
								$refDetailArr['remarks1']		=	$refValue;
							
							if($task=='create'){
								$recon->insertRecord($refDetailArr, $refTable);
							}else if($task=='edit'){
								$recon->updateRecord($refDetailArr, $refTable, "ref_name='$oldmodtype' AND name='$refIndex'");
							}
						}
						
						
					//if modtype is about						
				if($modulename=='about'){
					if($task=='create'){
						$aboutRefArr['id']				= 	getID($refTable);	
						$aboutRefArr2['id']				= 	getID($refTable);	
					}	
						$aboutRefArr['ref_name'] 		= 	"is_multiple";
						$aboutRefArr['name'] 			= 	"yes";
						$aboutRefArr['remarks1'] 		= 	$modtype;
						
						$aboutRefArr2['ref_name'] 		= 	$modtype;
						$aboutRefArr2['name'] 			= 	"introduction";
						$aboutRefArr2['remarks1'] 		= 	"published";
						$aboutRefArr2['remarks2'] 		= 	"";
		
						if($task=='create'){
								$recon->insertRecord($aboutRefArr, $refTable);
								$recon->insertRecord($aboutRefArr2, $refTable);
						}else if($task=='edit'){
								$recon->updateRecord($aboutRefArr, $refTable, "remarks1='$oldmodtype' AND ref_name='is_multiple'");
								$recon->updateRecord($aboutRefArr2, $refTable, "remarks1='$oldmodtype' AND name='introduction'");
						} 
				}
						/*******************************************************************************************/
						
					 header("location:index.php?mod=$mod&type=$type");
				}
			}
    } 
 
//LISTING
$paginate_arr['addquery'] 		=	"";

	//SEARCH  
	if (isset($_POST['psearch_entry'])) {
		$parse_arr 					=	array('pg', 'search');
		$searchloc 					=	(string) URL_Parser($_SERVER['QUERY_STRING'], $parse_arr) . "&search=" . $_POST['psearch_entry'];
		header("Location:?" . $searchloc);
	}
	if (isset($_GET['search'])) {
		$psearch 					=	trim($_GET['search']);
		$searchArray 				=	array("modulename", "modulecaption","modulestat","modulegroup","stat");
		$module->setSearchVal($psearch);
		$module->setSearchArray($searchArray);
		$searchresult				=	$module->searchValue();
		$paginate_arr['addquery'] 	= 	$searchresult;
	}

$paginate_arr['paginatequery'] 		=	"SELECT id, modulename, modulecaption, modulestat, menuorder, modulegroup, type, stat, is_category, is_postingdate, is_author, is_image, is_title, is_content, is_tag, imagepath FROM $dbTable WHERE modulestat='back' AND stat='active' AND modulegroup='Modules'";
$paginate_arr['query'] 				=	"ORDER BY modulename DESC";



//call the function  where you will pass your array of queries for the class' future use
$modulerecord->manage_record_queries($paginate_arr);
//get pagination results then assign to $article list
$list					= 	$modulerecord->get_paginated_array($items);
$result 				= 	$list['result'];
$num_rows 				= 	$list['num_rows'];
$PAGINATION_LINKS	 	= 	$list['PAGINATION_LINKS'];
$PAGINATION_INFO 		= 	$list['PAGINATION_INFO'];
$PAGINATION_TOTALRECS 	= 	$list['PAGINATION_TOTALRECS'];
  

//DELETE FROM LIST
if (isset($_POST['hidden_selected'])) {
	$itemUrl 	=	(isset($_GET['items'])) 	? "&items=".$_GET['items'] 		: "";
	$searchUrl 	=	(isset($_GET['search'])) 	? "&search=".$_GET['search'] 	: "";	
	
    if (count($_POST['hidden_selected']) > 0) {
        $str_ids 	=	implode(',', $_POST['delAnn']);		
		
		 
		
		$delArr = $_POST['delAnn'];
		foreach($delArr as $did){
			$task		= 	"delete";
			$title 		=	$core->getIdCaption("modulecaption",$dbTable,"id='$did'");
			$caption 	= 	(empty($title)) ? $type : $title;
			$activity 	=	"$task $caption";
			$core->logAdminTask($admin,$type,$task,$activity);
		}
		
        //delete selected
        //$recon->deleteRecord($dbTable, "id IN ($str_ids)");
		$arr['stat'] 	=	'inactive';
		$recon->updateRecord($arr, $dbTable, "id IN ($str_ids)");
        $url			=	"index.php?mod=$mod&type=".$type.$itemUrl.$searchUrl;	
		header("location:$url");
    }
}

if ($task == 'delete') {	 
	$itemUrl 	=	(isset($_GET['items'])) 	? "&items=".$_GET['items'] 		: "";
	$searchUrl 	=	(isset($_GET['search'])) 	? "&search=".$_GET['search'] 	: "";	
		
	 
	
	$title = $core->getIdCaption("modulecaption",$dbTable,"id='$sid'");
	$caption 	= 	(empty($title)) ? $type : $title;
	$activity 	=	"$task $caption";	
	$core->logAdminTask($admin,$type,$task,$activity);
	
	$arr['stat'] 	=	'inactive';
	$recon->updateRecord($arr, $dbTable, "id='$sid'");   
    $url		=	"index.php?mod=$mod&type=".$type.$itemUrl.$searchUrl;	
	header("location:$url");
}


 


?>

