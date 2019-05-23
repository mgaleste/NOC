<?
$recon 				=	new recordUpdate();
$mform 				=	new formMaintenance();
$coreMaintenance	=	new coreMaintenance(); 
$admin				=	$_SESSION['gp_username'];
$datetime			=	date("Y-m-d H:i:s"); 
 
$isEnableJob		=	$coreMaintenance->enableModule("jobs","careers");
$isEnableContact	=	$coreMaintenance->enableModule("contact","contact_us");

$errmsg 			=	array();
$errmsg[] 			=	isset($_GET['errmsg']) ? $_GET['errmsg'] : "";
$error_messages 	= 	"";

//Initialize Variables
$jobemail			= "";
$adminemail			= "";
$adminname			= "";
    
//Insert and Update Records
if (isset($_POST['save'])) {
	
		$jobemail 				=	htmlentities(addslashes(trim($_POST['jobemail'])));  
		$jobname 				=	htmlentities(addslashes(trim($_POST['jobname'])));  
		$adminemail				=	htmlentities(addslashes(trim($_POST['adminemail'])));
		$adminname				=	htmlentities(addslashes(trim($_POST['adminname'])));
		
		$jobArr['name'] 		=	$jobemail;        
		$jobArr['remarks1'] 	=	$jobname;        
		$recon->updateRecord($jobArr,"reference"," ref_name='job_email'");
		
		$adminArr['name'] 		=	$adminemail;        
		$adminArr['remarks1'] 	=	$adminname;        
		$recon->updateRecord($adminArr,"reference"," ref_name='admin_email'");
		 
		$error_messages 		=	implode('', $errmsg);
		if (empty($error_messages)) {
			header("location:index.php?mod=$mod&type=$type");
		}
	
}else{
		//RETRIEVE VALUES
		$arrayValues		 	=	array('name','remarks1');
		$retArray 				=	$recon->retrieveEntry("reference",$arrayValues,"","ref_name='job_email'");
		foreach($retArray as $retIndex=>$retValue){	
				$$retIndex 		=	$retValue;	
				$mainArr 		=	explode('|',$$retIndex);				 				 	 
				$jobemail		=	$mainArr[0];			 
				$jobname		=	$mainArr[1];			 
		}
		
		$arrayValues2 			=	array('name','remarks1');
		$retArray2 				=	$recon->retrieveEntry("reference",$arrayValues2,"","ref_name='admin_email'");
		foreach($retArray2 as $retIndex=>$retValue){	
				$$retIndex 		=	$retValue;	
				$mainArr2 		=	explode('|',$$retIndex);				 				 	 
				$adminemail		=	$mainArr2[0];			 
				$adminname		=	$mainArr2[1];			 
		}

}
  
?>