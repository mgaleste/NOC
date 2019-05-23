<?
$recon 				=	new recordUpdate();
$mform 				=	new formMaintenance();
$coreMaintenance	=	new coreMaintenance(); 
$coreFunctions		=	new coreFunctions(); 
$admin				=	$_SESSION['gp_username'];
$datetime			=	date("Y-m-d H:i:s"); 
 
$isEnableJob		=	$coreMaintenance->enableModule("jobs","jobs");
$isEnableContact	=	$coreMaintenance->enableModule("contact","contact_us");

$errmsg 			=	array();
$errmsg[] 			=	isset($_GET['errmsg']) ? $_GET['errmsg'] : "";
$error_messages 	= 	"";

//Initialize Variables
$main_charset		=	"";
$main_title			=	"";
$main_author		=	"";
$main_description	=	"";
$main_keywords		=	"";
$logo				=	"";
    
//Insert and Update Records
if (isset($_POST['save'])) {
	 
		$main_charset		=	htmlentities(addslashes(trim($_POST['main_charset'])));  
		$main_title			=	htmlentities(addslashes(trim($_POST['main_title'])));
		$main_author		=	htmlentities(addslashes(trim($_POST['main_author'])));
		$main_description	=	htmlentities(addslashes(trim($_POST['main_description'])));
		$main_keywords		=	htmlentities(addslashes(trim($_POST['main_keywords'])));
		 
		$metaArr['main_charset']		=	$main_charset;
		$metaArr['main_title']			=	$main_title;
		$metaArr['main_author']			=	$main_author;
		$metaArr['main_description']	=	$main_description;
		$metaArr['main_keywords']		=	$main_keywords;
		
		
		$logo 							=	$_FILES['logo']['name'];
		if(!empty($logo)){
			move_uploaded_file($_FILES["logo"]["tmp_name"],"./view/template1/images/" . $logo);
			$metaArr['logo']			=	$logo;
			if(file_exists("./view/template1/images/$oldlogo")){
				unlink("./view/template1/images/$oldlogo");
			}
		}
		
		foreach($metaArr as $metaIndex => $metaValue){	 
			$metaAr['name'] = $metaArr[$metaIndex];
			$recon->updateRecord($metaAr,"reference"," ref_name='$metaIndex'");			
		}
		
		$error_messages 		=	implode('', $errmsg);
		if (empty($error_messages)) {
			$task		=	"update";
			$caption 	=	$type;
			$activity 	=	"$task $caption";
			$coreFunctions->logAdminTask($admin,$type,$task,$activity);          
			header("location:index.php?mod=$mod&type=$type");
		}
	
}else{
	//RETRIEVE VALUES		
	$metaArr = array('main_charset','main_title','main_author','main_description','main_keywords','logo');
	foreach($metaArr as $metaVal){	
		$arrayValues		 	=	array('name');
		$retArray 				=	$recon->retrieveEntry("reference",$arrayValues,"","ref_name='$metaVal'");
		foreach($retArray as $retIndex=>$retValue){	
				$$retIndex 		=	$retValue;	
				$mainArr 		=	explode('|',$$retIndex);				 				 	 
				$$metaVal		=	$mainArr[0];				
		}
		$oldlogo	=	$logo;
	}
	
}


  
?>