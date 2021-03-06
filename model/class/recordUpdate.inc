<?

class recordUpdate extends connectUpdate {
		
		private $sqlQuery;
		private $items	=	10;
		 

//**-------------RETRIEVE CUSTOM ENTRY FROM THE DATABASE----------------------**/	
    public function retrieveCustomQuery($query) {
        $conn 		= $this->getDbConnection();        
        $errmsg 	= ""; 
		$sql = $query;
		//SET SQL QUERY
		self::setSqlQuery($sql);        
		$stmt = mysqli_query($conn, $sql);
        $returnArray 	= array();
        $returnArr 		= array();
        
        if($stmt){
		    while ($row = mysqli_fetch_assoc($stmt)) {
		        foreach ($row as $rowIndex => $rowValue) {
		            $returnArray[$rowIndex] = $rowValue;
		        }
		        $var 			= implode('|', $returnArray);
		        $returnArr[] 	= $var;
		    }
		
			mysqli_free_result($stmt);
		}
        return $returnArr;
    }
	
//**-------------RETRIEVE ENTRY FROM THE DATABASE----------------------**/	
    public function retrieveEntry($table, $arrayValues, $jointable='', $condition) {
        $conn 		= $this->getDbConnection();
        $db_fields 	= "";
        $errmsg 	= "";

        if (empty($arrayValues)) {
            echo "Incomplete Parameters Passed";
            die();
        }

        if (!is_array($arrayValues)) {
            echo "Parameter Passed is not an Array";
            return false;
        }

        foreach ($arrayValues as $v) {
            $db_fields .= $v . ",";
        }

			$db_fields = substr($db_fields, 0, -1);

        if (!empty($condition)) {
            $condition = "WHERE $condition";
        } else {
            $condition = "";
        }

		$sql = "SELECT $db_fields FROM $table $jointable $condition ";
		//SET SQL QUERY
		self::setSqlQuery($sql);
        
		$stmt = mysqli_query($conn, $sql);	 
		 
		
        $returnArray 	= array();
        $returnArr 		= array();
        
        if($stmt){

		    while ($row = mysqli_fetch_assoc($stmt)) {
		        foreach ($row as $rowIndex => $rowValue) {
		            $returnArray[$rowIndex] = $rowValue;
		        }
		        $var 			= implode('|', $returnArray);
		        $returnArr[] 	= $var;
		    }
		
			mysqli_free_result($stmt);
		}
        return $returnArr;
    }

//**-------------INSERT RECORDS DYNAMICALLY TO THE DATABASE----------------------**/	
    public function insertRecord($arrayValues, $table) {
        $conn = $this->getDbConnection();
        mysqli_autocommit($conn, false);

        $table_index = "";
        $table_value = "";

        if (empty($arrayValues)) {
            echo "Incomplete Parameters Passed";
            die();
        }

        if (!is_array($arrayValues)) {
            echo "Parameter Passed is not an Array";
            return false;
        }

        foreach ($arrayValues as $ind => $v) {
            $table_index .= $ind . ",";
            $table_value .= "'" . $v . "',";
        }

        $table_index 	= substr($table_index, 0, -1);
        $table_value 	= substr($table_value, 0, -1);

    	$sql 		= "INSERT INTO $table ($table_index) VALUES ($table_value)";

        //Check if inserted to table, if not rollback
        mysqli_query($conn, $sql);
        if (mysqli_errno($conn)) {
            $errno 	= mysqli_errno($conn);
            /*$sql 	= "SELECT id, message FROM errormsgs WHERE id='$errno'";
            $result = mysqli_query($conn, $sql);

            if ($row = mysqli_fetch_assoc($result)) {
                mysqli_rollback($conn);
                return $row["message"];
            }*/
            return $errno;
        } else {
            mysqli_commit($conn);
        }
    }

//**-------------UPDATE RECORDS DYNAMICALLY TO THE DATABASE----------------------**/			
    public function updateRecord($arrayValues, $table, $condition) {
        $conn = $this->getDbConnection();
        mysqli_autocommit($conn, false);

        $table_value = "";

        if (empty($arrayValues)) {
            echo "Incomplete Parameters Passed";
            die();
        }

        if (!is_array($arrayValues)) {
            echo "Parameter Passed is not an Array";
            return false;
        }

        foreach ($arrayValues as $ind => $v) {
            $table_value .= $ind . "= '" . $v . "',";
        }

        $table_value = substr($table_value, 0, -1);

	$sql 		=	"UPDATE $table SET $table_value WHERE $condition";

        //Check if inserted to table, if not rollback
        mysqli_query($conn, $sql);
        if (mysqli_errno($conn)) {
            $errno 	= mysqli_errno($conn);
            $sql 	= "SELECT id, message FROM errormsgs WHERE id='$errno'";
            $result = mysqli_query($conn, $sql);

            if ($row = mysqli_fetch_assoc($result)) {
                mysqli_rollback($conn);
                return $row["message"];
            }
        } else {
            mysqli_commit($conn);
        }
    }

//**-------------DELETE RECORD/S FROM THE DATABASE----------------------**/		
    public function deleteRecord($table, $condition) {
        $conn = $this->getDbConnection();
        mysqli_autocommit($conn, false);

        $sql3 = "DELETE FROM `$table` WHERE $condition";
        mysqli_query($conn, $sql3);
        if (mysqli_errno($conn)) {
            $errno 	= mysqli_errno($conn);
			$sql 	= "SELECT id, message FROM errormsgs WHERE id='$errno'";
            $result = mysqli_query($conn, $sql);

            if ($row = mysqli_fetch_assoc($result)) {
                mysqli_rollback($conn);
                $row["id"];
                $row["message"];
                if ($row["id"] == 1451) {
                    return "Cannot Delete a Record";
                }
            }
        } else {
            mysqli_commit($conn);
        }
    }

	/**RETRIEVE SINGLE VALUE**/
	public function GetValue($field,$fieldtable,$condition,$orderby=""){
		$value = '';
		$orderByClause = ($orderby)? " ORDER BY $orderby " : "";
		$con = $this->getDbConnection();
		$rec_query = "SELECT $field FROM $fieldtable WHERE $condition $orderByClause ";
		$stmt 	= mysqli_query($con, $rec_query);
		$result = (!empty($stmt)) ? $stmt->num_rows : 0;
		
		if($result > 0){
		    if ($row = mysqli_fetch_assoc($stmt)) {	 
				  $value .= stripslashes($row[''.$field.'']);
			}
		}
		return $value;
	}

	private function setSqlQuery($sqlQuery){
		$this->sqlQuery = $sqlQuery;
	}
	
	public function getSqlQuery(){
		return $this->sqlQuery;
	}
	
	public function setItems($items){
		$this->items = $items;
	}
	
	public function getItems(){
		return $this->items;
	}
	 
	//PAGINATION
	public function paginationQuery($query){
		$items 										=	self::getItems();
		$pagination_array							=	array();
		$pagination 								= 	new MyPagina ();
		$pagination->sql 							= 	$query ;
	    $pagination->rows_on_page					=	$items;
		$pagination_array['result']					=	$pagination->get_page_result();
		$pagination_array['num_rows']				=	$pagination->get_page_num_rows();
		$pagination_array['PAGINATION_LINKS']		=	$pagination->navigation(" | ", " | ");
		$pagination_array['PAGINATION_INFO']		=	$pagination->page_info();
		$pagination_array['PAGINATION_TOTALRECS']	=	$pagination->get_total_rows();
	
		return $pagination_array;
	}
	
	/**CUSTOMIZED QUERY FOR CREATE/TRUNCATE/DROP TABLE**/
	public function customizedQuery($query)
	{
		$conn = $this->getDbConnection();
        mysqli_autocommit($conn, false); 
        
        mysqli_query($conn, $query);
        if (mysqli_errno($conn)) {
            $errno 	= mysqli_errno($conn);
			/*$sql 	= "SELECT id, message FROM errormsgs WHERE id='$errno'";
            $result = mysqli_query($conn, $sql);
			if ($row = mysqli_fetch_assoc($result)) {*/
            mysqli_rollback($conn);
            
            return $errno;
            /*$row["message"];
            return $row["message"];
            }*/
        } else {
            mysqli_commit($conn);
        }
	}
	
}
?>
