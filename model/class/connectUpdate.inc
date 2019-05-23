<?php
class connectUpdate{

	public function __construct($server=W_SERVER,$username=W_USER,$password=W_PASSWORD,$database=W_DATABASE) {	
					$this->server 		= $server;
					$this->username 	= $username;
					$this->password		= $password;
					$this->database 	= $database;
				  
					if (mysqli_connect_error()) {
					    die('Connect Error (' . mysqli_connect_errno() . ') '
					            . mysqli_connect_error());
					}	
	}

//**---------------------GET DATABASE CONNECTION----------------------------**/				
	public function getDbConnection(){
				$conn = new mysqli($this->server, $this->username, $this->password, $this->database);
				return $conn;				 
	}	

}

?>