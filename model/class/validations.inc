<?php	
	class validations extends validationFunctions{
				
			private $returnResult 		= FALSE; 
								
			public function __construct($server2=W_SERVER,$username=W_USER,$password=W_PASSWORD,$database2=W_DATABASE) {	
					$this->server 		= $server2;
					$this->username 	= $username;
					$this->password		= $password;
					$this->database 	= $database2;
				  
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
			
			public function validations($validationValue,$validationType,$validationName="",$validationTable=""){
				
				if(!empty($validationValue)){
							switch($validationType){
								case 'mobile':
										if(!self::validate_mobile_number($validationValue)){			
										return	 $this->returnResult =  "Mobile number [ $validationValue ] is not valid numeric format.<br/>";
										} 
									break;		
								
								case 'phone':						
										if(!self::validate_telephone_number($validationValue)){											
										return	$this->returnResult =  "Phone number [ $validationValue ] is not valid numeric format.<br/>";
										} 
									break;		
													
								case 'fax':
										if(!self::validate_telephone_number($validationValue)){											
										return	$this->returnResult =  "Fax number [ $validationValue ] is not valid numeric format.<br/>";
										}  
									break;
									
							 	case 'email':										
										if (!filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL))
									    {									    
											return	$this->returnResult =  "Email [ $validationValue ] is not valid.<br/>";
									    }
									break;
								
								case 'postalcode':
										if(!self::validate_postalcode($validationValue)){											
											return	$this->returnResult =  "Postal code [ $validationValue ] is not valid numeric format.<br/>";
										}  
									break;
								
								case 'tinno':
										if(!self::validate_tinno($validationValue)){											
											return	$this->returnResult =  "Tin No. [ $validationValue ] is not valid numeric format.<br/>";
										}  
									break;
								
								case 'url':
										if(!self::validate_url($validationValue)){											
											return	$this->returnResult =  "Website [ $validationValue ] is not valid format.<br/>";
										}
									break;
								
								case 'required':			 	
										foreach($validationValue as  $validValue){
											return	$this->returnResult =  "Fields with * should not be blank.<br/>";
										}
									break;
									
								case 'alphanumeric':										 									 									   
										if (!isAlphaNumeric($validationValue)) {										
											return	$this->returnResult = "[ $validationValue ] is not valid alpha or numeric format.<br/>";
										}
									break;																 
								
								case 'numeric':										 
										if (!isNumeric($validationValue)) {
											return	$this->returnResult = "[ $validationValue ] is not valid numeric format.<br/>";
										}
									break;
									
								case 'decimal':										 
										if (!filter_input(INPUT_POST, "$validationName", FILTER_VALIDATE_FLOAT)){										
											return	$this->returnResult = "[ $validationValue ] is not valid decimal format.<br/>";
										}
									break;
									
								case 'money':										 									 	
										if (!self::validMoney($validationValue)) {																								
											return	$this->returnResult = "[ $validationValue ] is not valid money format.<br/>";
										}
									break;
									
								case 'alpha':										 										
										if (!isAlpha($validationValue)) {
											return	$this->returnResult = "[ $validationValue ] is not valid alpha format.<br/>";
										}
									break;
									
								case 'exists':	
										foreach($validationValue as  $validValue){
											if (self::validateExistEntry($validationValue,$validationTable)) {																								
												//return	$this->returnResult = "[ $validValue ] already exist.<br/>";
												return	$this->returnResult = "Duplicate entry found.<br/>";
											}
										}
									break;	
								
								case 'check_period':											
										if (self::validatePeriod($validationValue,$validationTable)) {																																		
												return	$this->returnResult = "Period should be in between 1 to 12 only.<br/>";
										}
										
									break;	
									
								case 'check_fiscalyear':											
										if (self::validateFiscalYear($validationValue,$validationTable)) {																																		
												return	$this->returnResult = "Fiscal Year is not a valid year format.<br/>";
										}
										
									break;	
									
								default: 
									return $this->returnResult = FALSE;
									break;									
							}				
				}				
			}			
	}

?>
