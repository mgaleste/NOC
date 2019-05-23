<?
$recon 				= new recordUpdate();
$mform 				= new formMaintenance();
$coreMaintenance	= new coreMaintenance();
$coreFunctions		= new coreFunctions();
$imgfunc 			= new imageFunctions();
$modulerecord 		= new record('links');
$sid 				= (!empty($_GET['sid'])) ? $_GET['sid'] : "";
$admin				= $_SESSION['gp_username'];
$datetime			= date("Y-m-d H:i:s"); 

$errmsg 			= array();
$errmsg[] 			= isset($_GET['errmsg']) ? $_GET['errmsg'] : "";
$bimg 				= isset($_GET['img']) ? $_GET['img'] : "";
$error_messages 	= "";

//Initialize Variables
$phone				= "";
$fax				= "";
$address			= "";
$email				= "";

 
   

//Insert and Update Records
if (isset($_POST['save'])) {
	
    $phone 		= htmlentities(addslashes(trim($_POST['phone'])));  
    $fax 		= htmlentities(addslashes(trim($_POST['fax'])));
    $email 		= htmlentities(addslashes(trim($_POST['email'])));
	$address 	= htmlentities(addslashes(trim($_POST['address'])));

    $contactArr['name'] 		= $phone;
    $contactArr['remarks1'] 	= $fax;
    $contactArr['remarks2'] 	= $email;
    $contactArr['remarks3'] 	= $address;
	$recon->updateRecord($contactArr,"reference"," ref_name='contact_details'");
     
    $error_messages = implode('', $errmsg);
    if (empty($error_messages)) {
		$task		=	"update";
		$caption 	=	$type;
		$activity 	=	"$task $caption";
		$coreFunctions->logAdminTask($admin,$type,$task,$activity);          
	
		
        header("location:index.php?mod=$mod&type=$type");
    }
}else{
//RETRIEVE VALUES
	$arrayValues 	= array('name','remarks1','remarks2','remarks3');
	$retArray 		= $recon->retrieveEntry("reference",$arrayValues,"","ref_name='contact_details'");
	foreach($retArray as $retIndex=>$retValue){	
			$$retIndex 		= $retValue;	
			$mainArr 		= explode('|',$$retIndex);				 				 	 
			$phone			= $mainArr[0];
			$fax			= $mainArr[1];
			$email			= $mainArr[2];
			$address		= $mainArr[3];
	}

}
  
?>