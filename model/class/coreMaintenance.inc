<?php
class coreMaintenance extends imageFunctions{
		
		private $databaseTable;
		private $fieldArray;
		private $coreTask;
		private $coreMod;
		private $coreType;
		private $coreDate;
		private $coreMonth;
		private $coreYear;
		private $corePreview;
		private $coreDbTable;
 
		  
		//SETTING IMAGES
		public function setImage($is_image,$imagename,$task,$type,$oldimagepath=""){
					$pageimagepath = "";
					if($is_image){
							$fname5 	= (isset($_POST[$imagename]) && (!empty($_POST[$imagename]))) ? $_POST[$imagename] : "";
							$tmp_name5 	= $_FILES[$imagename]["tmp_name"];
							$name5 		= $_FILES[$imagename]["name"];
							$conname5 	= str_replace(" ","_",$name5);
							$conname25 	= $type."_".$conname5; 		
		 
							if (!empty($name5)) {
									$pageimagepath 	= $conname25;
									$imagepath 		= $pageimagepath;
									$ext 			= explode('.', $pageimagepath);
									$imgname 		= str_replace(" ", "_", $ext[0]);
									$imtype 		= $ext[1];
									
									//check if set image file format is valid
									$fileType		=	array('jpg','gif','png');
									if(!in_array($imtype,$fileType))
										return false;
									
									move_uploaded_file($tmp_name5, "../uploads/$type/$conname25");						 	
									$ftmp 			= "../uploads/$type/" . $imgname . "." . $imtype;
									$ftmplarge 		= "../uploads/$type/large/";
									$ftmpthumb 		= "../uploads/$type/thumb/";
									$ftmpmain 		= "../uploads/$type/";
									$pimage 		= $imgname . '.' . $imtype;
									$result 		= self::resizePic($type, $ftmp, $imgname, $imtype);							
									self::removePicfromFolder("../uploads/$type/", $pimage);
									
									if ($task == 'edit' && $imagepath != $oldimagepath) {
										if (file_exists($ftmpthumb . $oldimagepath)) {
											self::removePicfromFolder($ftmpthumb, $oldimagepath);
										}

										if (file_exists($ftmplarge . $oldimagepath)) {
											self::removePicfromFolder($ftmplarge, $oldimagepath);
										}
									}
									
									if (file_exists($ftmplarge . "Thumbs.db")) {
									    self::removePicfromFolder($ftmplarge, "Thumbs.db");
									}

									if (file_exists($ftmpthumb . "Thumbs.db")) {
										self::removePicfromFolder($ftmpthumb, "Thumbs.db");
									}

									if (file_exists($ftmpmain . "Thumbs.db")) {
										self::removePicfromFolder($ftmpmain, "Thumbs.db");
									}
							} else {
								if ($task == 'edit'){
									$pageimagepath = $oldimagepath;
								}
							}
					}		 
				return $pageimagepath; 
		}
		
		//MOVE FILE LOCATION
		public function copyFile($oldpath,$newpath){				 
			$result = copy($oldpath, $newpath);
			return $result;
		}
		
		//REMOVE a FILE
		public function removeFile($source,$file){
			$result = self::removePicfromFolder($source,$file);
			return $result;
		}
		
		//SETTING FILE
		public function setFile($imagename,$task,$type,$oldfilepath=""){
							$filepath	=	"";
					
				 //echo $POST_MAX_SIZE = ini_get('post_max_size');
				 echo "<br/>";
				 echo $UPLOAD_MAX_SIZE = ini_get('upload_max_filesize');
				 echo 		ini_set('upload_max_filesize', '20M');
				  echo "<br/>";
							$fname5 	= (isset($_POST[$imagename]) && (!empty($_POST[$imagename]))) ? $_POST[$imagename] : "";
							$tmp_name5 	= $_FILES[$imagename]["tmp_name"];
							$name5 		= $_FILES[$imagename]["name"];
							$conname5 	= str_replace(" ","_",$name5);
							$conname25 	= $type."_".$conname5; 		
		 
							if (!empty($name5)) {
									$filepath	 	= $conname25;														
									if (file_exists("../uploads/$type/$filepath")){
											echo $_FILES[$imagename]["name"] . " already exists. ";
									}else{
										move_uploaded_file($tmp_name5, "../uploads/$type/$conname25");
										echo "Stored in: " . "../uploads/$type/" . $_FILES[$imagename]["name"];
									}	
									
									if ($task == 'edit' && $filepath != $oldfilepath) {
										if (file_exists("../uploads/$type/$oldfilepath")) {
											self::removePicfromFolder("../uploads/$type/", $oldfilepath);
										} 			 
									} 
							} else {
								if ($task == 'edit'){
									$filepath = $oldfilepath;
								}
							}
				 		 
				return $filepath; 
		}
		
		
	//SAVE DATABASE
		public function saveDatabase($task,$databaseTable,$fieldArray,$sid=""){
					switch ($task){
						case "create":
							return self::insertRecord($fieldArray, $databaseTable); 
						break;
						case "edit":
							if($databaseTable!='groups'){
								return self::updateRecord($fieldArray, $databaseTable, $databaseTable."Id='$sid'");
							}else{
								return self::updateRecord($fieldArray, $databaseTable, "groupCode='$sid'");
							}
						break;
					} 
		}
		
	//CREATE PERMALINK
		public function createPerma($title){
			$perma = "";
			if (!empty($title)) {
				$perma .= str_replace(" ", "-", $title);
			}		 				
			return $perma;
		}
		
		
	//CREATE TASK BUTTON
		public function createTaskButton($mod,$type){
			
			$task		=	ucwords(str_replace("_"," ",$type));
			$action 	=	"<a href=\"index.php?mod=$mod&type=$type&task=create\"><img src=\"".MODIMAGES."create.gif\" border=\"0\" alt=\"\"> Create $task</a>";
			return $action;
		}
		
	//CHECK IF ENABLE MODULE
		public function enableModule($mod,$type){		
				$conn 		=	$this->getDbConnection();
				mysqli_autocommit($conn, false);
				$isEnable	=	"";
				$sql 		=	"SELECT stat FROM modules WHERE modulename='$mod' AND type='$type'";
				$stmt		=	mysqli_query($conn, $sql);

			if ($row = mysqli_fetch_assoc($stmt)) {
				$isEnable 	=	$row['stat'];					
			}
			
			return $isEnable;		
		}
		
	//GET PRODUCT CATEGORY PARENT
		public function parentCategory($id,$table="itemcategory"){		
				$conn 		=	$this->getDbConnection();
				mysqli_autocommit($conn, false);
				$cap		=	"";
				$sql 		=	"SELECT caption FROM $table WHERE id='$id'";
				$stmt		=	mysqli_query($conn, $sql);

			if ($row = mysqli_fetch_assoc($stmt)) {
				$cap 		=	$row['caption'];					
			}
			
			return $cap;		
		}
		 
		public function setCoreTask($coreTask){
			$this->coreTask = $coreTask;
		}
	
		public function getCoreTask(){
			return $this->coreTask;
		}
		
		public function setCoreMod($coreMod){
			$this->coreMod = $coreMod;
		}
	
		public function getCoreMod(){
			return $this->coreMod;
		}
		
		public function setCoreType($coreType){
			$this->coreType = $coreType;
		}
	
		public function getCoreType(){
			return $this->coreType;
		} 
		
		public function setCoreMonth($coreMonth){
			$this->coreMonth = $coreMonth;
		}
	
		public function getCoreMonth(){
			return $this->coreMonth;
		}
		
		public function setCoreYear($coreYear){
			$this->coreYear = $coreYear;
		}
	
		public function getCoreYear(){
			return $this->coreYear;
		}
		
		public function setCorePreview($corePreview){
			$this->corePreview = $corePreview;
		}
	
		public function getCorePreview(){
			return $this->corePreview;
		}
		
		public function setCoreDbTable($coreDbTable){
			$this->coreDbTable = $coreDbTable;
		}
	
		public function getCoreDbTable(){
			return $this->coreDbTable;
		} 
		
		//Convert date to database date format
		public function saveDateToDb($seperator,$coreDate){
			$dateArr 		= explode($seperator,$coreDate);
			$reviseDate		= $dateArr[2].'-'.$dateArr[0].'-'.$dateArr[1];
			return	$reviseDate;
		}
		
		//Retrieve Date From Database in month, day, year format with desired seperator
		public function retrieveDateFromDb($coreDate,$seperator2="/",$seperator="-"){
			$dateArr 		= explode($seperator,$coreDate);
			$reviseDate		= $dateArr[1].$seperator2.$dateArr[2].$seperator2.$dateArr[0];
			return	$reviseDate;
		}
		
		//Retrieve Meta Details of Apanel and Main site
		public function retrieveMetaSettings($metaArray){				
			 foreach($metaArray as $metaVal){	
				$$metaVal = "";
			 }
			 
			 foreach($metaArray as $metaVal){	
				 
					$arrayValues		 	=	array('name');
					$meta 					= 	array();
					$retArray 				=	self::retrieveEntry("reference",$arrayValues,"","ref_name='$metaVal'");
					foreach($retArray as $retIndex => $retValue){	
							$$retIndex 		=	$retValue;	
							$mainArr 		=	explode('|',$$retIndex);				 				 	 
						 	$$metaVal		=	$mainArr[0];						
					}						
			 }
			 
			 foreach($metaArray as $metaVal){	
				$meta[] .= $$metaVal;
			 }
						 
			 return $meta;
		}
		
}
?>
