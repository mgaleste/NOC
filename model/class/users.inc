<?php
class users extends recordUpdate{
	
	private $userArray;
	private $userId;
	private $userTable;
	private $userTask;
	private $userCond;
	private $userHeader;
	private $joinTable;
	private $userName;
	private $userEmail;
	 
	 	 
	public function checkUserExist($userName, $userEmail){
					$exist			=	false;
			//Retrieving User Details	
				$arrayValues2 		=	array('username','email');
				$retArray2 			=	self::retrieveEntry("users", $arrayValues2, "", "username='$userName' AND email='$userEmail'");
				foreach ($retArray2 as $retIndex => $retValue){
					$$retIndex 		=	$retValue;
					$mainArr 		=	explode('|', $$retIndex);
					$username	 	=	$mainArr[0];					
					$email	 		=	$mainArr[1];
					$exist			=	true;
				}				
				return $exist;
	}
	
	public function forgotPassword($userName, $userEmail){					
					$username	 	=	"";
					$email	 		=	"";
					$firstname	 	=	"";
					$lastname	 	=	"";
					$userId		 	=	"";
					
			 //Retrieving Admin Email and Info
				$arrayValues 		=	array('name','remarks1');
				$retArray 			=	self::retrieveEntry("reference", $arrayValues, "", "ref_name='admin_email'");
				foreach ($retArray as $retIndex => $retValue) {
					$$retIndex 		=	$retValue;
					$mainArr 		=	explode('|', $$retIndex);
					$adminemail 	=	$mainArr[0];
					$adminname		=	$mainArr[1];			
				}
				
			//Retrieving User Details	
				$arrayValues2 		=	array('username','email','firstname','lastname','id');
				$retArray2 			=	self::retrieveEntry("users", $arrayValues2, "", "username='$userName' AND email='$userEmail'");
				foreach ($retArray2 as $retIndex => $retValue){
					$$retIndex 		=	$retValue;
					$mainArr 		=	explode('|', $$retIndex);
					$username	 	=	$mainArr[0];					
					$email	 		=	$mainArr[1];					
					$firstname	 	=	$mainArr[2];					
					$lastname	 	=	$mainArr[3];					
					$userId		 	=	$mainArr[4];					
				} 
				
				//Generate New Password
				$generatepassword	=	self::genPassword(12);
				
				//Update Generated Password to DATABASE
				$coreMaintenance				=	new coreMaintenance();
				$usersArray['id']		 		=	$userId;		
				$usersArray['password']		 	=	md5($generatepassword);
				$coreMaintenance->saveDatabase("edit","users",$usersArray,$userId);
				
				$msgBody 	 = "<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
				$msgBody 	.= "<tr><td><b>Username : </b></td><td>$username<br/></td></tr>";
				$msgBody 	.= "<tr><td><b>Password : </b></td><td>$generatepassword<br/></td></tr>";
				$msgBody 	.= "</table>";
				
				$txtBody	 = "Username : $username\n\n";
				$txtBody	.= "Password : $generatepassword\n\n";
			
			//Sending Email to New Admin User
				$mail		=	new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
				$mail->IsSendmail(); // telling the class to use SendMail transport
				try {					
					//  $mail->AddBCC("lumeng.lim@cid-systems.com", "Lumeng Lim");
					  $mail->AddReplyTo($email, $firstname . $lastname);
					  $mail->AddAddress($email, $firstname . $lastname);
					  $mail->SetFrom($adminemail, $adminname);
					  $mail->AddReplyTo($email, $firstname . $lastname);
					  $mail->Subject = "Forgot Password";
					  //$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically 
					  $mail->AltBody = $txtBody;
					  $mail->MsgHTML($msgBody);
					  $mail->Send();	
							$status = "send";
					} catch (phpmailerException $e) {
					    $e->errorMessage(); //Pretty error messages from PHPMailer
					} catch (Exception $e) {
					    $e->getMessage(); //Boring error messages from anything else!
					} 
					
					return $status;					
	}
	
	
	public function resetPassword($userId){
			 //Retrieving Admin Email and Info
				$arrayValues 		=	array('name','remarks1');
				$retArray 			=	self::retrieveEntry("reference", $arrayValues, "", "ref_name='admin_email'");
				foreach ($retArray as $retIndex => $retValue) {
					$$retIndex 		=	$retValue;
					$mainArr 		=	explode('|', $$retIndex);
					$adminemail 	=	$mainArr[0];
					$adminname		=	$mainArr[1];			
				}
				
			//Retrieving User Details	
				$arrayValues2 		=	array('username','email','firstname','lastname');
				$retArray2 			=	self::retrieveEntry("users", $arrayValues2, "", "id='$userId'");
				foreach ($retArray2 as $retIndex => $retValue){
					$$retIndex 		=	$retValue;
					$mainArr 		=	explode('|', $$retIndex);
					$username	 	=	$mainArr[0];					
					$email	 		=	$mainArr[1];					
					$firstname	 	=	$mainArr[2];					
					$lastname	 	=	$mainArr[3];					
				} 
				
				//Generate New Password
				$generatepassword	=	self::genPassword(12);
				
				//Update Generated Password to DATABASE
				$coreMaintenance				=	new coreMaintenance();
				$usersArray['id']		 		=	$userId;		
				$usersArray['password']		 	=	md5($generatepassword);
				$coreMaintenance->saveDatabase("edit","users",$usersArray,$userId);
				
				$msgBody 	 = "<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
				$msgBody 	.= "<tr><td><b>Username : </b></td><td>$username<br/></td></tr>";
				$msgBody 	.= "<tr><td><b>Password : </b></td><td>$generatepassword<br/></td></tr>";
				$msgBody 	.= "</table>";
				
				$txtBody	 = "Username : $username\n\n";
				$txtBody	.= "Password : $generatepassword\n\n";
			
			//Sending Email to New Admin User
				$mail		=	new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
				$mail->IsSendmail(); // telling the class to use SendMail transport
				try {					
					//  $mail->AddBCC("lumeng.lim@cid-systems.com", "Lumeng Lim");
					  $mail->AddReplyTo($email, $firstname . $lastname);
					  $mail->AddAddress($email, $firstname . $lastname);
					  $mail->SetFrom($adminemail, $adminname);
					  $mail->AddReplyTo($email, $firstname . $lastname);
					  $mail->Subject = "Reset Password";
					  //$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically 
					  $mail->AltBody = $txtBody;
					  $mail->MsgHTML($msgBody);
					  $mail->Send();							
					} catch (phpmailerException $e) {
					    $e->errorMessage(); //Pretty error messages from PHPMailer
					} catch (Exception $e) {
					    $e->getMessage(); //Boring error messages from anything else!
					} 
	}
	
	
	
			public function genPassword($size=9, $power=0) {
				$vowels = 'aeuy';
				$randconstant = 'bdghjmnpqrstvz';
				if ($power & 1) {
					$randconstant .= 'BDGHJLMNPQRSTVWXZ';
				}
				if ($power & 2) {
					$vowels .= "AEUY";
				}
				if ($power & 4) {
					$randconstant .= '23456789';
				}
				if ($power & 8) {
					$randconstant .= '@#$%';
				}

				$Randpassword = '';
				$alt = time() % 2;
				for ($i = 0; $i < $size; $i++) {
					if ($alt == 1) {
						$Randpassword .= $randconstant[(rand() % strlen($randconstant))];
						$alt = 0;
					} else {
						$Randpassword .= $vowels[(rand() % strlen($vowels))];
						$alt = 1;
					}
				}
				return $Randpassword;
			}	
	
	public function userSendEmail($userTask){
		if($userTask=='create'){			 
			 //Retrieving Admin Email and Info
				$arrayValues 		=	array('name','remarks1');
				$retArray 			=	self::retrieveEntry("reference", $arrayValues, "", "ref_name='admin_email'");
				foreach ($retArray as $retIndex => $retValue) {
					$$retIndex 		=	$retValue;
					$mainArr 		=	explode('|', $$retIndex);
					$adminemail 	=	$mainArr[0];
					$adminname		=	$mainArr[1];			
				}
			
			//Retrieving User Details	
					$userArray		=	self::getUserArray();		
				 foreach($userArray as $retIndex => $retVal){
					 $$retIndex 	=	$retVal;
				 } 
								
				$msgBody 	 = "<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";
				$msgBody 	.= "<tr><td><b>Username : </b></td><td>$username</td></tr>";
				$msgBody 	.= "<tr><td><b>Password : </b></td><td>$generatepassword</td></tr>";
				$msgBody 	.= "<tr><td><b>First Name : </b></td><td>$firstname</td></tr>";
				$msgBody 	.= "<tr><td><b>Last Name : </b></td><td>$lastname</td></tr>";
				$msgBody 	.= "<tr><td><b>Email : </b></td><td>$email</td></tr>";
				$msgBody 	.= "<tr><td><b>Phone : </b></td><td>$phone</td></tr>";
				$msgBody 	.= "<tr><td><b>Registered Date : </b></td><td>$registered_date</td></tr>";
				$msgBody 	.= "</table>";
			
			//Sending Email to New Admin User
				$mail		=	new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
				$mail->IsSendmail(); // telling the class to use SendMail transport
				try {					
					 // $mail->AddBCC("lumeng.lim@cid-systems.com", "Lumeng Lim");
					  $mail->AddReplyTo($email, $firstname . $lastname);
					  $mail->AddAddress($email, $firstname . $lastname);
					  $mail->SetFrom($adminemail, $adminname);
					  $mail->AddReplyTo($email, $firstname . $lastname);
					  $mail->Subject = "New Admin User";
					  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically 
					  $mail->MsgHTML($msgBody);
					  $mail->Send();							
					} catch (phpmailerException $e) {
					    $e->errorMessage(); //Pretty error messages from PHPMailer
					} catch (Exception $e) {
					    $e->getMessage(); //Boring error messages from anything else!
					} 
		}
	}
	
	public function userRetrieveInfo(){
		$userId		=	self::getUserId();
		$userTable	=	self::getUserTable();
		$userArray	=	self::getUserArray();
		$condition = ($userTable=="groups")? "groupCode='$userId'" : "username='$userId'";
		$retArray	=	self::retrieveEntry($userTable, $userArray, "", $condition);
		
		foreach ($retArray as $retIndex => $retValue) {
            $$retIndex 		= $retValue;
            $mainArr 		= explode('|', $$retIndex);
			$returnValue  	= array();			 	 
			foreach ($mainArr as $mainArrIndex => $mainArrValue) {					
					$returnValue[] = $mainArrValue;			
			}			         
        }
		return $returnValue;
	}
	
	public function userGroupAccessRetrieveInfo(){
		$userCond				=	self::getUserCond();
		$userTable				=	self::getUserTable();		
		$userArray				=	self::getUserArray();		
		$retArray				=	self::retrieveEntry($userTable, $userArray, "", "$userCond");
		$groupAccessArr			= 	array();
		foreach ($retArray as $retIndex => $retValue) {
            $$retIndex 			=	$retValue;
            $mainArr 			=	explode('|', $$retIndex);
			$returnValue  		=	array();			 	 			 
			$groupAccessArr[] 	=	$mainArr[1];
        }
		return $groupAccessArr;
	}
	
	public function userGroupTaskAccessRetrieveInfo(){
		$userCond				=	self::getUserCond();
		$userTable				=	self::getUserTable();		
		$userArray				=	self::getUserArray();		
		$retArray				=	self::retrieveEntry($userTable, $userArray, "", "$userCond");
		$groupAccessArr			= 	array();
		foreach ($retArray as $retIndex => $retValue) {
            $$retIndex 			=	$retValue;
            $mainArr 			=	explode('|', $$retIndex);
			$returnValue  		=	array();			 	 			 
			$groupAccessArr[] 	=	$mainArr[0];
        }
		return $groupAccessArr;
	}
	
	public function setUserArray($userArray){
		$this->userArray = $userArray;
	}
	
	public function getUserArray(){
		return $this->userArray;
	}
	
	public function setUserId($userId){
		$this->userId = $userId;
	}
	
	public function getUserId(){
		return $this->userId;
	}
	
	public function setUserTable($userTable){
		$this->userTable = $userTable;
	}
	
	public function getUserTable(){
		return $this->userTable;
	}
	
	public function setJoinTable($joinTable){
		$this->joinTable = $joinTable;
	}
	
	public function getJoinTable(){
		return $this->joinTable;
	}
	
	public function setUserCond($userCond){
		$this->userCond = $userCond;
	}
	
	public function getUserCond(){
		return $this->userCond;
	}
	
	public function setUserHeader($userHeader){
		$this->userHeader = $userHeader;
	}
	
	public function getUserHeader(){
		return $this->userHeader;
	}
	
	  
}
?>
