<?php
class connection
{  var $db;                    
   var $resultSet;             
   var $rs;                   
   var $rscount;              
   var $errormsg;             
	
   var $dbname;					 
   var $user;           
   var $pass;           
   var $server;        
   
   function connection()
   {	include_once("dbconfig.php");
   		$this->server=W_SERVER;
   		$this->user=W_USER;
		$this->pass=W_PASSWORD;
		$this->dbname=W_DATABASE;
		
		$dbname=$this->dbname;
		$user=$this->user;
		$pass=$this->pass;
		$server=$this->server;
   }
	function setDb($user,$pass,$dbname)
	{	$this->dbname=$dbname;
		$this->user=$user;
		$this->pass=$pass;
	}
   function connDb()
   {	$this->db=mysql_connect($this->server,$this->user,$this->pass)or $this->errormsg.=mysql_error()."error connecting to database";
	   	mysql_select_db($this->dbname)or $this->errormsg.=mysql_error()."error with database connection";
   }
   function disconnDb()
   {	mysql_close($this->db);
   }
   function selectDb($strString)
   {	$this->resultSet=mysql_query($strString, $this->db)or $this->errormsg=mysql_error();
      	$this->rscount=mysql_num_rows($this->resultSet);
      	if(strlen($this->errormsg)>0)
        	return false;
      	return true;
   }
   function qselectDb($strString)
   {  $this->connDb();
      $this->resultSet=mysql_query($strString, $this->db)or $this->errormsg=mysql_error();
      $this->rscount=@mysql_num_rows($this->resultSet);
      $this->disconnDb();
   }
   function executeDb($strString)
   {	mysql_query($strString,$this->db)or $this->errormsg=mysql_error();
      	if(strlen($this->errormsg)>0)
        	return false;
      	return true;
   }
   function qexecuteDb($strString)
   {  	$this->connDb();
      	mysql_query($strString,$this->db)or $this->errormsg=mysql_error();
      	$this->disconnDb();
      	if(strlen($this->errormsg)>0)
        	return false;
      	return true;
   }
   function fetchRs()
   {	$this->rs=@mysql_fetch_array($this->resultSet);
      	return $this->rs;
   }
	function hasError()
	{	if(strlen($this->errormsg)>0)
			return true;
		return false;
	}
	function external_dbc()
	{
	mysql_connect('localhost','root','123456');
	mysql_select_db('alcon');
	return true;
	}
}