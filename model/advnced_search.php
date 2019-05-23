<?php
function searchtable($table='',$searchstr='')
	{
	
		$search_results=array();
		$i = 0;
		$field_names=array();
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
			// the variable i contains the number of fields in a table
		$c1 = new connection();
		$counter=1;
		//echo $table;
		//echo $i;
		$result_ctr=0;
		while($counter<$i)//loop within the fieldnames ; start after the id field
			{
			//echo '</br>counter: '.$counter;
			//echo '</br>sstr: '.$searchstr;
			//echo '</br>fname: '.$field_names[$counter];
			$sql="SELECT * FROM ".$table." WHERE ".$field_names[$counter]." like '%".$searchstr."%'";
			//echo '</br>'.$sql.'</br>';
			$c1->qselectDb($sql);
			
			while($c1->fetchRs())
			{
				$search_results[$result_ctr]= $c1->rs['id'];
				//echo '</br>ID: '.$c1->rs['id'];
				$result_ctr++;
			}
			$counter++;
			//print_r($search_results);
			}
			//print_r($search_results);
			//loop/search complete. now transfer the results to another array so that it will hold unduplicated values
			$tempstr="";
			$temp_ctr=count($search_results);
			//echo '</br>teut:'.$temp_ctr;
			$sresults=array(); //the new array
			$sresults[0]=$search_results[0]; //place id of first found result
			$tempstr=$sresults[0];
			
			for($ctr=1;$ctr<$temp_ctr;$ctr++){
				if(in_array($search_results[$ctr],$sresults)){
				}else{
					$sresults[$ctr]=$search_results[$ctr];
					$tempstr=$tempstr.','.$sresults[$ctr];
					}
											}			
			$sresults=explode(",",$tempstr);// this will be the array u will use
			return $sresults;	
			//return $tempstr;
			//return $search_results;
						
	}
	
	function return_all_fields($table){
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
	
	
	
?>