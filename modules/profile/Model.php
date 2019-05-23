<?php 
$mform				=	new formMaintenance();
$admin_uid 			=	$_SESSION['gp_username'];
$errmsg 			=	"";
$table 				=	'users';

if($type=='pass'){
		$curr_pass = "";
		$new_pass = "";
		$renew_pass = "";
		$con = new connection();
		$mode = $_GET['mode'];
		
		if(isset($_POST['buttones'])){
				$curr_pass 	= trim($_POST['curr_pass']);
				$new_pass 	= trim($_POST['new_pass']);
				$renew_pass = trim($_POST['renew_pass']);
				
				if($mode!='newloginagency'){
					$con->qselectDb("SELECT * FROM $table WHERE username='$admin_uid'");
					
					if($con->fetchRs()){
						$dbpass = $con->rs['password'];	
					}	

					if($curr_pass == ''){
						$errmsg = "Current Password must not be blank.";
					}else if(md5($curr_pass) != $dbpass){
						$errmsg = "Current password is incorrect.";
					}else if($new_pass == ''){
						$errmsg = "New Password must not be blank.";
					}else if($renew_pass == ''){
						$errmsg = "Retype New Password must not be blank.";
					}else if($new_pass != $renew_pass){
						$errmsg = "New Password didn't match.";
					}	

					if($errmsg == ''){		
						$encrypt_new_pass = md5($new_pass);		
						$sql_upd = "UPDATE $table SET password='$encrypt_new_pass' WHERE username='$admin_uid'";
						$con->qexecuteDb($sql_upd);			
						$curr_pass = "";
						$new_pass = "";
						$renew_pass = "";		
						$errmsg = "Password successfully changed.";
						if(empty($mode)){
							header("location:index.php?mod=profile&type=info&task=view");				
						}else{
							$dateNow = date("Y-m-d H:i:s");
							$sql_upd = "UPDATE users SET lastLogin='$dateNow' WHERE username='$admin_uid'";
							$con->qexecuteDb($sql_upd);
							header("location:index.php");				
						}
					}
				}else{
					$con->qselectDb("SELECT * FROM agency_signatories WHERE username='$admin_uid'");
					
					if($con->fetchRs()){
						$dbpass = $con->rs['password'];	
					}	

					if($curr_pass == ''){
						$errmsg = "Current Password must not be blank.";
					}else if(md5($curr_pass) != $dbpass){
						$errmsg = "Current password is incorrect.";
					}else if($new_pass == ''){
						$errmsg = "New Password must not be blank.";
					}else if($renew_pass == ''){
						$errmsg = "Retype New Password must not be blank.";
					}else if($new_pass != $renew_pass){
						$errmsg = "New Password didn't match.";
					}	

					if($errmsg == ''){		
						$encrypt_new_pass = md5($new_pass);		
						$sql_upd = "UPDATE agency_signatories SET password='$encrypt_new_pass' WHERE username='$admin_uid'";
						$con->qexecuteDb($sql_upd);			
						$curr_pass = "";
						$new_pass = "";
						$renew_pass = "";		
						$errmsg = "Password successfully changed.";
						if(empty($mode)){
							header("location:index.php?mod=profile&type=info&task=view");				
						}else{
							$dateNow = date("Y-m-d H:i:s");
							$sql_upd = "UPDATE agency_signatories SET lastLogin='$dateNow' WHERE username='$admin_uid'";
							$con->qexecuteDb($sql_upd);
							header("location:index.php");				
						}
					}
				}				
		}

}elseif($type=='info'){

		if(isset($_POST['Edit'])){
			$lname 		= trim($_POST['lname']);
			$fname 		= trim($_POST['fname']);
			$mname 		= trim($_POST['mname']);
						
			if($errmsg == ''){
				//$sql_upd = "UPDATE `$table` SET lname='$lname', fname='$fname', mname='$mname', address='$address'  , phone='$phone' , email='$email' WHERE id='$admin_uid'";
				$sql_upd = "UPDATE users SET firstName = '".$fname."',middleName = '".$mname."',lastName = '".$lname."' WHERE username='$admin_uid'";
				$con->qexecuteDb($sql_upd);				
				$errmsg = "Profile was successfully updated.";
				header("location:index.php?mod=profile&type=info&task=view");				
			}	
		}else{
			$con->qselectDb("SELECT users.*  FROM users JOIN groups ug USING(groupCode) WHERE users.username='$admin_uid'");
			if($con->fetchRs()){
				$lname 			= $con->rs['lastName'];
				$fname 			= $con->rs['firstName'];
				$mname 			= $con->rs['middleName'];
				$username 		= $con->rs['username'];
				$status_name 	= $con->rs['systemStatus'];
			}
		}
}


?>	
