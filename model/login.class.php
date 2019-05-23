<?php
class Login {
	protected $connection; 
	protected $uname;  
	protected $password;  
	protected $tablename;  
	
	public function __construct()
		{
			$this->connection = new connection();				
		}
	
	public function check_login($uname, $password)
		{	
			$tablename = "users";		
			$con = $this->connection;					
			$con->qselectDb("SELECT * FROM $tablename WHERE username='".mysql_escape_string($uname)."' AND password='$password' AND stat='active'");			
			
			$chk = 'out';
			if($con->fetchRs()){
				$id = $con->rs['username'];
				//$_SESSION['gp_has_logged'] 	=	"verifieds";	
				$chk = 'in';
			}	
			
			if($chk=='in'){
				$browser = $this->check_browser($id);
				if($browser == ''){
				$this->set_status($id);
				//	var_dump($browser);
				}else{
					$chk = $browser;
				}
			}
			return $chk;		
	}
	
	public function check_browser($id)
		{
			$notice 	= "";
			$tablename 	= "users";			
			$con = $this->connection;					
			$con->qselectDb("SELECT * FROM $tablename WHERE username='$id'");			
			
			if($con->fetchRs()){
					$isLogin 	= $con->rs['isLogin'];
					$useragent 	= $con->rs['useragent'];
			}
			
			/*if($useragent!=$_SERVER['HTTP_USER_AGENT'].getIP() && $isLogin=='yes'){
				$notice  = "Cross Browser Not Allowed";
			}else if($useragent==$_SERVER['HTTP_USER_AGENT'].getIP() && $isLogin=='yes'){
				$notice  = "";
			}else if($isLogin=='yes'){
				$notice  = "Already Login";
			}else if($isLogin=='no' || isset($_COOKIE['apstat'])){
				$notice  = "";
			}*/
			
			return $notice;
		
		}
		
	public function set_status($id)
		{
			$agent 		= createAgent();
			$tablename 	= "users";		
			$con = $this->connection;		
			$sql = "UPDATE $tablename SET `isLogin`='yes', `useragent`='$agent' WHERE username='$id' ";
			$con->qexecuteDb($sql);			
			$value = "logged";			
			//setcookie("apstat", $value, time()+3600);  /* expire in 1 hour */			 
			$_SESSION['gp_has_logged'] 	=	"verifieds";								
		}	
	
	
	
	public function get_fullname($uname)
		{
			$firstname	= "";
			$mname		= "";
			$lastname	= "";
			$tablename = "users";	
			//$tablename2 = "usersdetail";	
			
			$con = $this->connection;
			echo $con->qselectDb("SELECT firstName, lastName, middleInitial from $tablename 
			WHERE username='".mysql_escape_string($uname)."'");	
		 
			if($con->fetchRs()){
				$firstname 		= $con->rs['firstName'];
				$mname		 	= $con->rs['middleInitial'];
				$lastname	 	= $con->rs['lastName'];
			}
			
			$uname = $firstname.''.$lastname;			
			return $uname;
		}	
	
	//SESSION
	public function get_session($uname)
	{				 
			$tablename 		= "users";		
			//$tablename2 		= "usersdetail";		
			$con			= $this->connection;					
			 
			$con->qselectDb("SELECT username, stat, firstName, lastName, middleInitial from $tablename WHERE username='".mysql_escape_string($uname)."'");	
			 
			if($con->fetchRs()){			
				$tempsession_array = array();
				
				//$tempsession_array['ap_id']					=	$con->rs['usersid'];										
				$tempsession_array['username'] 		=	$con->rs['username'];										
				$tempsession_array['firstName'] 	=	$con->rs['firstname'];										
				$tempsession_array['lastName'] 		=	$con->rs['lastname'];										
				 
				return $tempsession_array;			
			}	
	}
	
	
	//USER-COMPANY
	public function get_user_company($uid)
	{	 	
			$tablename 		= "userscompany";					
			$con			= $this->connection;	
			$con->qselectDb("SELECT userscompanyid, username, companycode from $tablename 
			WHERE username='$uid'  ORDER BY companycode");	
			
			if($con->fetchRs()){			
				$tempsession_array = array();				
				$tempsession_array['userscompanyid']	=	$con->rs['userscompanyid'];										
				$tempsession_array['username'] 			=	$con->rs['username'];										
				$tempsession_array['companycode'] 		=	$con->rs['companycode'];										
			 		
				return $tempsession_array;			
			}	
	}
	
	// Logout 
	function user_logout()
	{		
			$tablename = "users";
			$con 		= $this->connection;		
			$username 	= $_SESSION['username'];
			$sql 		= "UPDATE $tablename SET `isLogin`='no', `useragent`='' WHERE username='$username' ";
			$con->qexecuteDb($sql);
			$_SESSION['gp_has_logged'] 	=	"verifieds";					
			//setcookie("apstat", "", time()-3600);
			unset($_SESSION['gp_has_logged']);				 		
			$_SESSION['gp_has_logged'] = FALSE;
			// kill session variables
			$_SESSION = array(); // reset session array
			session_destroy();   // destroy session.
	}
	
}
?>
