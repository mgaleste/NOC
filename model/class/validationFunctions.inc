<?php

	class validationFunctions{
			
			private $patterns;
			private $regex;
						 
			//VALIDATE PHONE NUMBER
			public function validate_telephone_number($value){
						   
						  $this->patterns = array('^([0-9]{3})-([0-9]{4})$'
						  	,'^([0-9]{4})-([0-9]{3})$'
						  	,'^([0-9]{3})-([0-9]{2})-([0-9]{2})$'				  	
							,'^([0-9]{7})$'
							,'^([0-9]{2})-([0-9]{7})$'
							,'^([0-9]{2})-([0-9]{3})-([0-9]{2})-([0-9]{2})$'
							,'^([0-9]{2})-([0-9]{4})-([0-9]{3})$'
							,'^([0-9]{2})-([0-9]{3})-([0-9]{4})$'
							,'^([0-9]{10})$'
							,'^\(\d{3}\) ([0-9]{3})-([0-9]{4})$'
							,'^\(\d{3}\) ([0-9]{3})-([0-9]{2})-([0-9]{2})$'
							,'^\(\d{3}\) ([0-9]{4})-([0-9]{3})$'
							,'^\(\d{3}\) ([0-9]{7})$'					
							,'^\+\(\d{2}\) ([0-9]{3})-([0-9]{4})$'
							,'^\+\(\d{2}\) ([0-9]{3})-([0-9]{2})-([0-9]{2})$'
							,'^\+\(\d{2}\) ([0-9]{4})-([0-9]{3})$'
							,'^\+\(\d{2}\) ([0-9]{7})$'					
							,'^\(\d{2}\) ([0-9]{3})-([0-9]{2})-([0-9]{2})$'
							,'^\(\d{2}\) ([0-9]{4})-([0-9]{3})$'
							,'^\(\d{2}\) ([0-9]{3})-([0-9]{4})$'
							,'^\(\d{2}\) ([0-9]{7})$'					
							,'^\(\+\d{2}\) ([0-9]{3})-([0-9]{4})$'
							,'^\(\+\d{2}\) ([0-9]{3})-([0-9]{2})-([0-9]{2})$'
							,'^\(\+\d{2}\) ([0-9]{4})-([0-9]{3})$'
							,'^\(\+\d{2}\) ([0-9]{7})$'			
							,'^\(\+\d{2}\)([0-9]) ([0-9]{3})-([0-9]{4})$'		
							,'^\(\+\d{2}\)([0-9]) ([0-9]{4})-([0-9]{3})$'		
							,'^\(\+\d{2}\)([0-9]) ([0-9]{3})-([0-9]{2})-([0-9]{2})$'		
							,'^\(\+\d{2}\)([0-9]) ([0-9]{7})$'		
						  );
						    
						$this->regex = '/(' .implode('|', $this->patterns) .')/i';
						return (preg_match($this->regex, $value)) ? true : false;				
			}
								
			//VALIDATE MOBILE NUMBER			 
			public function validate_mobile_number($value){ 				 
						  $this->patterns = array('^([0-9]{4})-([0-9]{4})-([0-9]{3})$'
						  	,'^([0-9]{4})-([0-9]{2})-([0-9]{2})-([0-9]{3})$'				  	
						  	,'^([0-9]{4})-([0-9]{2})-([0-9]{5})$'				  	
							,'^\+([0-9]{2})-([0-9]{3})-([0-9]{2})-([0-9]{2})-([0-9]{3})$'
							,'^\+([0-9]{5})-([0-9]{2})-([0-9]{2})-([0-9]{3})$'
							,'^\+([0-9]{12})$'
							,'^\+([0-9]{7})-([0-9]{5})$'
							,'^([0-9]{11})$'
						  	,'^([0-9]{4})-([0-9]{7})$'
							,'^\(\+\d{2}\)([0-9]{5})-([0-9]{5})$'
							,'^\(\+\d{2}\)([0-9]{10})$'
							,'^\(\+\d{2}\)([0-9]{3})-([0-9]{2})-([0-9]{2})-([0-9]{3})$'
							,'^\(\+\d{2}\)-([0-9]{3})-([0-9]{2})-([0-9]{2})-([0-9]{3})$'					 
						  );
						    
						$this->regex = '/(' .implode('|', $this->patterns) .')/i';			
						return (preg_match($this->regex, $value)) ? true : false;		 
			}
			
			//VALIDATE POSTAL CODE		 
			public function validate_postalcode($value){ 				 
						$this->patterns = array('^([0-9]{4})$');
						    
						$this->regex = '/(' .implode('|', $this->patterns) .')/i';			
						return (preg_match($this->regex, $value)) ? true : false;		 
			}
			
			//VALIDATE TIN NO.
			public function validate_tinno($value){ 				 
						$this->patterns = array('^([0-9]{3})-([0-9]{3})-([0-9]{3})-([0-9]{3})$'
						,'^([0-9]{12})$'
						);
						    
						$this->regex = '/(' .implode('|', $this->patterns) .')/i';			
						return (preg_match($this->regex, $value)) ? true : false;		 
			}
			
		 	//VALIDATE URL			
			public function validate_url($url){					
						 $v = "/^(http|https|ftp|):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i";
    					 return (bool)preg_match($v, $url);
			}
			
			//VALIDATE MONEY
			public function validMoney($text)
			{  $string="1234567890., ";
			   if(substr_count($text,".")==1)
			   {
			      $cut=explode(".",$text);
			      if(strlen($cut[1])>2)
			         return false;
			   }
			   
			   if(substr_count($text,".")<=1){  
			   		for($i=0; $i<strlen($text);$i++)
			        	 if(substr_count($string,$text[$i])==0)
			            		return false;
			   			  }else
			      		return false;
			   		return true;
			}			
			
			//VALIDATE EXISTING ENTRY
			function validateExistEntry($entry,$table){	
						$conn = $this->getDbConnection();			 	
						$index 	= "";
						$value 	= "";
						$match 	= "";
						 
						foreach($entry as $i => $v){
							$index .= $i . ",";
							$value .= "'".$v . "',";
							$match .= $i . "=  '".$v . "' AND ";
						}
							
						$index		= substr($index,0,-1);
						$value	 	= substr($value,0,-1);
						$match	 	= substr($match,0,-4);
					
						if ($result = $conn->query("SELECT $index FROM $table WHERE $match")) {		    					
								($result->num_rows==0) ? $val = "" : $val =  $result->num_rows;								
							    /* free result set */
							    $result->close();
						}
						return $val;	
			}
			
			//VALIDATE PERIOD IF 1 to 12
			function validatePeriod($entry,$table){	
						 if($entry>12){
						 	$val = true;
						 }else{
						 	$val = false;
						 }
						return $val;	
			}
			
			function validateFiscalYear($value){							 
						 $this->patterns = '^(d{4})$';						    
						 //$this->patterns = '^([0-9]{4})$';						    
						$this->regex = '/(' .$this->patterns.')/i';			
						return (preg_match($this->regex, $value)) ? true : false;	
			}			
	}

?>