<?
$username  			= 	''; 
$user_pass 			= 	''; 
$errmsg 			= 	'';
$user_groups_str	=	"";
$access_str			=	"";
$temp				=	array();
$user_groups 		=	array();
$accesses			=	array(); 
$useraccesses		=	array();
$access_ids1		=	array();

$ctrl      			=	"";
			
if(isset($_POST['Submit'])){	
	
	
	$username  = trim($_POST['user_name']);

	if($_POST['password'] != ""){		
		$user_pass = trim(md5($_POST['password']));
	}else if($_POST['password'] == ""){
		$user_pass = trim($_POST['password']);
	}
	
    if(trim($username) == ''){	    
        $errmsg = 'Invalid ID or password. Please try again.';    
    }else if(trim($user_pass) == ''){	    
	    $errmsg = 'Invalid ID or password. Please try again.';	    
    }else if(!isAlphaNumeric($username)){
	 $errmsg = 'Invalid ID or password. Please try again.';
	}else if(!isAlphaNumeric($user_pass)){
	 $errmsg = 'Invalid ID or password. Please try again.';
	}	
    
    if($errmsg == ""){
	    if($type!='agency_signatory'){
			$tempo_con = new connection();
			$tempo_con->qselectDb("SELECT * FROM users WHERE `username`='{$username}' AND `password`='{$user_pass}'");	
			if($tempo_con->fetchRs()){
					if($tempo_con->rs['systemStatus']!='active'){
						$errmsg = "Account Suspended. Please contact your webmaster";
					}else{
					$id 						= $tempo_con->rs['id']+1;
					$_SESSION['gp_id'] 			= $tempo_con->rs['id'];
					$_SESSION['gp_fname'] 		= $tempo_con->rs['firstName'];
					$_SESSION['gp_lname'] 		= $tempo_con->rs['lastName'];
					$_SESSION['gp_mname'] 		= $tempo_con->rs['middleInitial'];			
					$_SESSION['gp_username'] 	= $tempo_con->rs['username'];
				 
					$_SESSION['gp_group']		= $tempo_con->rs['groupCode'];			
					$_SESSION['gp_status'] 		= $tempo_con->rs['status'];
					//$_SESSION['gp_email'] 		= $tempo_con->rs['emailaddress'];
					//$_SESSION['gp_level'] 		= $tempo_con->rs['level_id'];
					//$_SESSION['gp_issp'] 		= $con->rs['issp'];
					$_SESSION['gp_has_logged'] 	= "verifieds";	
				
					$admin_me = $_SESSION['gp_id'];	
					//showLog('Logged In','adminlogs',$_SESSION['gp_username']);
					
					$core 		= 	new coreFunctions();				
					$admin		=	$_SESSION['gp_username'];
					$activity 	=	"Login";
					$core->logAdminTask($admin,"","login",$activity);
					
						if($tempo_con->rs['lastLogin']=='0000-00-00 00:00:00'){			
							header("Location: index.php?mod=profile&type=pass&mode=newlogin");	
						}else{
							header("Location: index.php");	
						}
					}			
			}else{	
				$errmsg = "Invalid ID or password. Please try again.";				
			}
		}else if($type=='agency_signatory'){
			$tempo_con = new connection();
			$tempo_con->qselectDb("SELECT * FROM agency_signatories WHERE `username`='{$username}' AND `password`='{$user_pass}'");	
			if($tempo_con->fetchRs()){
					if($tempo_con->rs['systemStatus']!='active'){
						$errmsg = "Account Suspended. Please contact your webmaster";
					}else{
					$id 						= $tempo_con->rs['id']+1;
					$_SESSION['gp_id'] 			= $tempo_con->rs['id'];
					$_SESSION['gp_name'] 		= $tempo_con->rs['agencySignatoryName'];
					$_SESSION['gp_username'] 	= $tempo_con->rs['username'];
					$_SESSION['gp_acode'] 		= $tempo_con->rs['agencyCode'];
					$_SESSION['gp_code'] 		= $tempo_con->rs['agencySignatoryCode'];
					$_SESSION['gp_group']		= 'signatory';			
					$_SESSION['gp_status'] 		= $tempo_con->rs['systemStatus'];
					$_SESSION['gp_email'] 		= $tempo_con->rs['email'];
					//$_SESSION['gp_level'] 		= $tempo_con->rs['level_id'];
					//$_SESSION['gp_issp'] 		= $con->rs['issp'];
					$_SESSION['gp_has_logged'] 	= "verifieds";	
				
					$admin_me = $_SESSION['gp_id'];	
					//showLog('Logged In','adminlogs',$_SESSION['gp_username']);
					
					$core 		= 	new coreFunctions();				
					$admin		=	$_SESSION['gp_username'];
					$activity 	=	"Login";
					$core->logAdminTask($admin,"","login",$activity);
					
						if($tempo_con->rs['lastLogin']=='0000-00-00 00:00:00'){			
							header("Location: index.php?mod=profile&type=pass&mode=newloginagency");	
						}else{
							header("Location: index.php");	
						}		
						//header("Location: index.php?mod=registration&type=registration_online");	
					}			
			}else{	
				$errmsg = "Invalid ID or password. Please try again.";				
			}
		}
    }        
}?>
