<?php
//include_once 'connection.php';
class record{
	var $tablename;
	var $current_task;
	var $rec_id;
	var $datetime;
	var $opt_query;
	var $query_addition;
	var $paginatequery;
	var $rec_conn;
	
	function record($table)
	{	
		/*include_once("dbconfig.php");
   		$this->server	=	W_SERVER;
   		$this->user		=	W_USER;
		$this->pass		=	W_PASSWORD;
		$this->dbname	=	W_DATABASE;
		*/
		$this->tablename =   $table;
		$this->rec_conn  =   new connection();		
		
	}
	
	
	///////////////////////////
	
	
		function insert_record($values){
			$table = $this->tablename;
			$true_index = "";
			$true_value = "";
			if(empty($values)){
				echo "Incomplete Parameters Passed";
				die();
			}
			
			if(!is_array($values)){
				echo "Parameter Passed is not an Array";
				return false;
			}
			
			foreach($values as $ind => $v){
				$true_index .= $ind . ",";
				$true_value .= "'".$v . "',";
			}
			
			$true_index = substr($true_index,0,-1);
			$true_value = substr($true_value,0,-1);
			
			$rec_query = "INSERT INTO $table ($true_index) VALUES ($true_value)";
			$this->rec_conn->qexecuteDb($rec_query);
			
		}
		
		function update_record($values, $condition){
			$table = $this->tablename;
			$true_index = "";
			$true_value = "";
			if(empty($table) || empty($values) || empty($condition)){
				echo "Incomplete Parameters Passed";
				die();
			}
		
			if(!is_array($values)){
				echo "Parameter Passed is not an Array";
				die();
			}
			
			foreach($values as $ind => $v){
				$true_value .= $ind ." = '".$v . "',";
			}
			
			$true_value = substr($true_value,0,-1);
			
			$rec_query ="UPDATE $table SET $true_value WHERE $condition";
			
			$this->rec_conn->qexecuteDb($rec_query);
			
		}
		
		function delete_record($condition){
			$table = $this->tablename;
			if(empty($table) || empty($condition)):
				echo "Incomplete Parameters Passed";
				die();
			endif;
		
			$rec_query = "DELETE FROM $table WHERE $condition";
			$this->rec_conn->qexecuteDb($rec_query);
			
		}
	
	//////////////////////////
	
	
	
	function update_record_settings($table,$task,$id){
		$this->tablename	=   $table;
		$this->current_task	=   $task;
		$this->rec_id		=   $id;
		$this->datetime		=   date("Y-m-d H:i:s");
	}
	
	function manage_record_queries($queryarray){
		$this->opt_query = $queryarray['query'];
		$this->query_addition = $queryarray['addquery'];
		$this->paginatequery = $queryarray['paginatequery'];
	}
	
	function retrieve_record(){


	}

	function return_all_fields(){
		$table=$this->tablename;
		$field_names=array();
		$i = 0;
		$c = new connection();
		
		$c->qselectDb("SELECT * FROM $table");

		while ($i < mysql_num_fields($c->resultSet)) 
			{
			$meta = mysql_fetch_field($c->resultSet, $i);
			if (!$meta) {
				echo "No information available<br />\n";
						}
			$field_names[$i]=$meta->name;
			$i++;
			}
		return $field_names;	
	} 
	
	function check_data_count(){
		$fieldnames=$this->return_all_fields();
		$idfield=$fieldnames[0];//id is always the first field regardless of caption
		$table=$this->tablename;
		$c = new connection();
		$c->qselectDb("SELECT COUNT($idfield) as rec_count FROM $table ");
		$c->fetchRs();
		$data_count=$c->rs['rec_count'];
		return $data_count;
	}
	
	function return_data($data_id){
		//set tablename based on the class' constructor
		$table=$this->tablename;
		$present_query = $this->opt_query;
		$query_additional = $this->query_addition;
		$tempquery="";
		
		if($present_query==""){
		    $tempquery="SELECT * FROM $table";
		}else{
		    $tempquery=$present_query.$query_additional;
		}
		
		//check if return data is bulk or return data is of singular record
		if($data_id==''){//bulk data
			$record_sql=$tempquery; //change this queries depending on uery needed
		}else{
			$record_sql=$tempquery." WHERE id='$data_id' ".$query_additional;
		}
		
		$fieldnames=$this->return_all_fields();
		$data_array=array();
		$c = new connection();
		$c->qselectDb($record_sql);
		$idfield=$fieldnames[0];
		$tmpctr=0;
		while($c->fetchRs()){
			foreach($fieldnames as $fields){
				$data_array[$tmpctr][$fields]=$c->rs[$fields];
				
			}
		$tmpctr++;
		}
		return $data_array;
	}
	
	
	
	function get_paginated_array($items){
		$pagination_query 	=	$this->paginatequery;
		$op_query 			=	($this->query_addition!="") ? $this->query_addition : "";
		$switch_query 		=	($this->opt_query!="") ? $this->opt_query : "";
		$pagination_array	=	array();
		$pagination 		= 	new MyPagina ();
		//echo $pagination_query.$op_query.$switch_query ;
		$pagination->sql 	= 	$pagination_query.$op_query.$switch_query ;
	    $pagination->rows_on_page	=	$items;
		$pagination_array['result']	=	$pagination->get_page_result();
		$pagination_array['num_rows']=	$pagination->get_page_num_rows();
		$pagination_array['PAGINATION_LINKS']	=	$pagination->navigation(" | ", " | ");
		$pagination_array['PAGINATION_INFO']	=	$pagination->page_info();
		$pagination_array['PAGINATION_TOTALRECS']=	$pagination->get_total_rows();
		return $pagination_array;
	}
	
	function retrieve_rec($condition=""){
	
		$table = $this->tablename;
			$rec_query	=	"SELECT * FROM $table".$condition;
			$this->rec_conn->qselectDb($rec_query);
			if($this->rec_conn->fetchRs()){
				$rec_rs = $this->rec_conn->rs;
				return $rec_rs;
			}else{
				return false;
			}
		}
	
	function retrieve_multi($arrayofconditions){
		$table = $this->tablename;
		$thisrecquery = "SELECT * FROM $table";
		$arrayofresults = 	array();
		$emptyarr		=	array();
		if(!empty($arrayofconditions)){
			foreach($arrayofconditions as $rec_query){
				echo $rec_query;
				$this->rec_conn->qselectDb($thisrecquery.$rec_query);
					if($this->rec_conn->fetchRs()){
						$rec_rs = $this->rec_conn->rs;
						$arrayofresults[] = $rec_rs;					
					}else{
						$arrayofresults[] = $emptyarr;	
					}
					                                  }
		                               }
		return $arrayofresults;
		}
		
		
	function check_existence($query){
			$this->rec_conn->qselectDb($query);
			if($this->rec_conn->fetchRs()){
				//$rec_rs = $this->rec_conn->rs;
				return true;
			}else{
				return false;
			}
	}	
	
	function retrieve_data($query,$multiple=false){
		$table = $this->tablename;
		$return_arr = array();
		$return_data = "";
		if(!$this->check_existence($query)){
			return false;
		}else{
			$this->rec_conn->qselectDb($query);
			//echo $query;
			if($multiple==false){
					$this->rec_conn->fetchRs();
					$return_data  = $this->rec_conn->rs;
					return $return_data;
			}else{
					$count = 0;
					while($this->rec_conn->fetchRs()){
						$return_arr[$count] = $this->rec_conn->rs;
						$count++;
					}
					return $return_arr;
			}
			
		}
	}
}

?>
