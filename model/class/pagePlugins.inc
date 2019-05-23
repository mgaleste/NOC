<?
class pagePlugins extends coreFunctions{
	private $array;
	private $plugArray;
	private $plugType;
	private $plugTable;
	private $pageLink;
	private $pageCaption;
	private $currentAccess;
	private $modType;
	private $modId;
	
	 
	public function setPageCaption($pageCaption){
		$this->pageCaption = $pageCaption;
	}
	
	public function getPageCaption(){
		return $this->pageCaption;
	}
	
	public function setPageLink($pageLink){
		$this->pageLink = $pageLink;
	}
	
	public function getPageLink(){
		return $this->pageLink;
	}
	
	public function setModType($modType){
		$this->modType = $modType;
	}
	
	public function getModType(){
		return $this->modType;
	}
	
	public function setModId($modId){
		$this->modId = $modId;
	}
	
	public function getModId(){
		return $this->modId;
	}
	
	public function setCurrentModAccess($currentAccess){
		$this->currentAccess = $currentAccess;
	}
	
	public function getCurrentModAccess(){
		return $this->currentAccess;
	}
	
	
	private function display_aboutchild2($parent, $level,$type,$editparent='') 
	{		
			$types = "";
			$value = "";
			$conn 		= self::getDbConnection();
			$query		= "SELECT * from `category` WHERE parent='$parent' AND type='$type'";
			$sql 		= $query;			
			$values		= (!empty($value)) ?  $value : "";
			$result = mysqli_query($conn,$sql);				 
				while($row = mysqli_fetch_array($result)){					 
					   		$value .=  "<option value=\"".$row['id']."\" >".str_repeat('-',$level).'&nbsp;'.stripslashes($row['caption'])."</option>";
							$value .= 	self::display_aboutchild2($row['id'], $level+1,$type,$editparent);
				}
		   return $value;
	}
	
	public function pagePlugin($types,$array,$dbTable,$triggers="",$dates="",$triggers2="",$dates2=""){
				$is_image		=	"";
				$is_category	=	"";
				$is_author		=	"";
				$is_postingdate =	"";
				$is_title		=	"";
				$is_content		=	"";
				$is_postingdate =	"";
				
				$mform 			= 	new formMaintenance();			
				$task			=	"create";
				$mod			=	"pages";
				self::setPlugType($types);				 
				self::setPlugArray($array);	
				self::setPlugTable($dbTable);
				$type 			=  self::getPlugType();
				self::pagePlugModel();
				$$array[0]		= 	DATE('m/d/Y'); 
				$$array[1]		=	DATE('H:i:s'); 
				$$array[2]		=	"";
				$$array[3]		=	"";
				$$array[4]		=	"";
				$$array[5]		= 	DATE('m/d/Y'); 
				$$array[6]		= 	""; 
				$$array[7]		= 	""; 
				 
				
				$oldimagepath 	= "";
				//Get Module Settings
				$modArray 		=	self::getModuleFunction($type);				
				foreach ($modArray as $modIndex => $modVal) {
					$$modIndex 		= $modVal;
					$mainArr 		= explode('|', $$modIndex);
					$is_category 	= $mainArr[1];    
					$is_postingdate	= $mainArr[2];    
					$is_author 		= $mainArr[3];
					$is_image 		= $mainArr[4];
					$is_title 		= $mainArr[5];
					$is_content 	= $mainArr[6];
					$is_tag 		= $mainArr[7];
				}
			
			$snipp 	= ""; 
			$snipp .= "<form method=\"POST\" enctype=\"multipart/form-data\">			 
							<table cellpadding=\"0\" cellspacing=\"10\" border=\"0\" width=\"100%\">
								<tr>
									<td colspan=\"2\">
										<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">											 
											<tr>
												<td valign=\"middle\">".$mform->label('publisheddate','Publish Date :','caption','req')."</td>
												<td>".$mform->inputBox($task,'text',$array[0],$$array[0],'iputdate input',$dates,'5','readonly')."&nbsp;".$mform->button($task,$triggers,'calbutton')."</td>
												<td valign=\"middle\">".$mform->label('publishedtime','Publish Time :','caption','req')."</td>
												<td>".$mform->inputBox($task,'text',$array[1],$$array[1],'iputdate input','','8','')."</td>
											</tr>
										</table>
									</td>
								</tr>							
								<tr><td colspan=\"2\"><hr/></td></tr>";
					if($is_image=='yes'){
					$snipp  .= "<tr>
									<td valign=\"middle\">".$mform->label('title','Image :','caption')."</td>
									<td><input type=\"file\" name=\"".$array[2]."\" id=\"".$array[2]."\"  onChange=\"changeImageCaption()\" />&nbsp;&nbsp;&nbsp;<input type=\"text\" readonly id=\"".$array[2]."caption\" class=\"changecap\"></td> 
								</tr>";
					}
					if($is_category=='yes'){
				 			if($is_category=='yes'){ 
								$snipp  .= "<tr>						
									<td valign=\"middle\" width=\"200px\">".$mform->label('category','Category','caption','req')."</td>
									<td>
										<select name=\"".$array[7]."\"  id=\"".$array[7]."\" class=\"iselect cat\">
										<option value=\"0\">- Select One -</option>";
										$snipp  .= self::display_aboutchild2('0','0',$type,'');
							$snipp  .= "</select>
										<a href=\"index.php?mod=category&type=$type\" target=\"_blank\" class=\"linkcategory\">ADD CATEGORY</a>
									</td>
								</tr>";						
							}
					}
					
					if($is_author=='yes'){
			   $snipp .= "<tr>
							<td valign=\"middle\">".$mform->label('author','Author :','caption')."</td>
							<td>".$mform->inputBox($task,'text',$array[6],$$array[6],'input iputsize width500',$array[6],'100')."</td>
						</tr>";
					}	

					if($is_postingdate=='yes'){
			  $snipp .= "<tr>
							<td valign=\"middle\">".$mform->label('postingdate','Posting Date','caption','req')."</td>
							<td>".$mform->inputBox($task,'text',$array[5],$$array[5],'iputdate input',$dates2,'5','readonly')."&nbsp;".$mform->button($task,$triggers2,'calbutton')."</td>
						</tr>";
					}
					
					if($is_title=='yes'){
				  $snipp .= "<tr>
									<td valign=\"middle\">".$mform->label('title','Title :','caption','req')."</td>
									<td>".$mform->inputBox($task,'text',$array[3],$$array[3],'input iputsize width500',$array[3],'200')."</td>
							</tr>";
					}
					
					if($is_content=='yes'){
					$snipp .= "<tr>
									<td valign=\"top\">".$mform->label('contents','Contents','caption')."</td>
									<td valign=\"top\">".$mform->textarea($task,$array[4],stripslashes(html_entity_decode($$array[4])),'10','20','tinymce txtarea',$$array[4])."</td>
							   </tr>";
					}		
						 
					$snipp .= "<tr><td colspan=\"2\">&nbsp;</td></tr>";						
					$snipp .= "<tr>
									<td align=\"left\"><input type=\"submit\"  name=\"publish$type\" value=\"Publish\" class=\"button2 roundbuttons\" onclick=\"change()\"></td>
									<td align=\"right\"><input type=\"submit\" name=\"savedraft$type\" value=\"Save Draft\" class=\"button2 roundbuttons \" onclick=\"change()\"></td>
								</tr>								 
						</table>
						<div class=\"message\"><p>";
						if(!empty($errmsg)){ foreach($errmsg as $errValue){ echo $errValue; } }
		$snipp .= "</p></div>
				</form>";
			 
	$snipp .= "<script type=\"text/javascript\">
				  Calendar.setup(
					{
					  inputField  : \"$dates\",  		// ID of the input field
					  ifFormat    : \"%m/%d/%Y\",      // the date format
					  button      : \"$triggers\"       // ID of the button
					}
				  ); ";
		if($is_postingdate=='yes'){		  
		$snipp .= "		  Calendar.setup(
					{
					  inputField  : \"$dates2\",  		// ID of the input field
					  ifFormat    : \"%m/%d/%Y\",      // the date format
					  button      : \"$triggers2\"       // ID of the button
					}
				  ); ";
		}
		
		$snipp .= "</script> 	 
	 
				<script type=\"text/javascript\" > 
						function changeImageCaption(){	 
									var test5	= document.getElementById('".$array[2]."').value;
									document.getElementById('".$array[2]."caption').value = test5;
						} 		
				</script>		
				<script type=\"text/javascript\">
				$(document).ready(function()
					{ 
						$(\"#".$array[7]."\").change(function(){			 
							var id				=	$(this).val();
							var dataString 		= 	'id='+ id;
							$.ajax
							({
								type:	\"POST\",
								url: 	\"modules/services/ajax_itemcategory.php\",
								data: 	dataString,
								cache: 	false,
								success: function(msg){
											$(\"#".$array[7]."container\").ajaxComplete(function(event, request, settings)
											{
								
												$(\"#".$array[7]."container\").html(msg);
											});
										} 
							}); 
						});
						
						 
					});

				</script>";
				 
		 
		return $snipp;
	}
	
	public function pagePlugModel(){
	 
		$currentdate 		= 	date('m/d/Y');
		$currenttime 		= 	date('H:i:s');
		$coreMaintenance 	=	new coreMaintenance();
		$type				=	self::getPlugType();
		$array 				=	self::getPlugArray();
		$dbTable			=	self::getPlugTable();
		$admin				=	$_SESSION['gp_username'];
		$datetime			=	date("Y-m-d H:i:s");  
		$errmsg 			= 	array();
		$savedraft 			= 	"savedraft$type";
		$publish 			= 	"publish$type";
		
		if (isset($_POST[$savedraft]) || isset($_POST[$publish])) {
					//Check Module Settings
					$modArray = self::getModuleFunction($type);
					foreach ($modArray as $modIndex => $modVal) {
						$$modIndex 		= $modVal;
						$mainArr 		= explode('|', $$modIndex);
						$is_category 	= $mainArr[1];    
						$is_postingdate	= $mainArr[2];
						$is_author 		= $mainArr[3];
						$is_image 		= $mainArr[4];
						$is_title 		= $mainArr[5];
						$is_content 	= $mainArr[6];
						$is_tag 		= $mainArr[7];
					}					
					
					$actionbutton 				=	(isset($_POST[$savedraft])) ? "draft" : "published";
					$pageid 					=	getID($dbTable);
					$pageArr['id'] 				=	$pageid;

					if($is_category == 'yes'){
						$catid 					= 	$array[7];
						$pageArr['catid'] 		=	(isset($_POST[$catid]) && (!empty($_POST[$catid]))) ? $_POST[$catid] : 1;
						$pageArr['remarks1']	=	(isset($_POST['subcatid']) && (!empty($_POST['subcatid']))) ? $_POST['subcatid'] : 1;
					}else{
						$pageArr['catid'] 		= 	1;
						$pageArr['remarks1'] 	= 	1;
					}

					if ($is_title == 'yes'){
						$title 					= 	$array[3];
						$pageArr['title'] 		= 	(isset($_POST[$title]) && (!empty($_POST[$title]))) ? htmlentities(trim($_POST[$title])) : "";
					}

					if ($is_content == 'yes') {		 
						$contents = $array[4];
						$pageArr['content'] 	= (isset($_POST[$contents]) && (!empty($_POST[$contents]))) ? addslashes(htmlentities(trim($_POST[$contents]))) : "";
					}
					
					if ($is_postingdate == 'yes') {		 
						$postingdate 				= 	$array[5];
						$ppostingdate 				= 	(isset($_POST[$postingdate]) && (!empty($_POST[$postingdate]))) ? $_POST[$postingdate] : "";						
						$spostingdate 				=	$coreMaintenance->saveDateToDb("/",$ppostingdate);
						$pageArr['postingdate'] 	=	$spostingdate;
					}
					
					if ($type == 'testimonials') {
						$pageArr['remarks1'] 		=	(isset($_POST['remarks1']) && (!empty($_POST['remarks1']))) ? addslashes(htmlentities(trim($_POST['remarks1']))) : "";			
					}

					if ($is_tag == 'yes') {
						$pageArr['tags'] 			=	(isset($_POST['tags']) && (!empty($_POST['tags']))) ? htmlentities(trim($_POST['tags'])) : "";
					}        
					
					if ($is_author == 'yes') {
						$author 					=	$array[6];
						$pageArr['author'] 			=	(isset($_POST[$author]) && (!empty($_POST[$author]))) ? htmlentities(trim($_POST[$author])) : "";
					}
 
					$publisheddate 					= 	$array[0];
					$publishedtime 					= 	$array[1];
					$ppublisheddate					= 	(isset($_POST[$publisheddate]) && (!empty($_POST[$publisheddate]))) ? $_POST[$publisheddate] : $currentdate;					
					$spublisheddate					=	$coreMaintenance->saveDateToDb("/",$ppublisheddate);
					$pageArr['publisheddate'] 		=	$spublisheddate;
					$publisheddate					=	$ppublisheddate;
					
					$pageArr['publishedtime'] 		=	(isset($_POST[$publishedtime]) && (!empty($_POST[$publishedtime]))) ? $_POST[$publishedtime] : $currenttime;
					
					$pageArr['type'] 				=	$type;
					$pageArr['status'] 				=	$actionbutton;
					 
					$pageArr['enteredby'] 			=	$admin;
					$pageArr['entereddate'] 		=	$datetime;
					$pageArr['updatedby'] 			=	$admin;
					$pageArr['updateddate']			=	$datetime;
					
					$pageArr['permalink'] 	=  	$coreMaintenance->createPerma($pageArr['title']);
		   					
					$title 					=	($is_title == 'yes') 	? $pageArr['title'] : "";
					$contents 				=	($is_content == 'yes') 	? $pageArr['content'] : "";        
					$author 				=	($is_author == 'yes') 	? $pageArr['author'] : "";
					$publishedtime 			=	$pageArr['publishedtime'];
				  
				if ($is_image == 'yes') {						 
					$imagepath 				=	$array[2];		 
					$pageArr['imagepath'] 	=	$coreMaintenance->setImage($is_image,$imagepath,"create",$type,"");
				}
				 
					$error_messages = implode('', $errmsg);
					if (empty($error_messages)) {
						$queryResult = self::insertRecord($pageArr, $dbTable);
						if (empty($queryResult)) {
							$caption = (empty($title)) ? $type : $title;
							$activity 	=	"create $caption";
							self::logAdminTask($admin,$type,"create",$activity);
							header("location:index.php");
						}
					}
    
		}
	}
	
	
	public function setPlugType($plugType){
		$this->plugType = $plugType;
	}
	
	public function getPlugType(){
		return $this->plugType;
	}
	
	public function setPlugArray($plugArray){
		$this->plugArray = $plugArray;
	}
	
	public function getPlugArray(){
		return $this->plugArray;
	}
	
	public function setPlugTable($plugTable){
		$this->plugTable = $plugTable;
	}
	
	public function getPlugTable(){
		return $this->plugTable;
	}
	
	public function productPlugin($types,$array,$dbTable2,$triggers="",$dates=""){
				
				$mform 			= 	new formMaintenance();			
				$task			=	"create";
				$mod			=	"products";
				  
				self::setPlugType($types);				 
				self::setPlugArray($array);	
				self::setPlugTable($dbTable2);
			 	$type 			=  self::getPlugType();
				self::productPlugModel();
				$$array[0]		= 	DATE('m/d/Y'); 
				$$array[1]		=	DATE('H:i:s'); 
				$$array[2]		=	"";
				$$array[3]		=	"";
				$$array[4]		=	"";
				$$array[5]		= 	""; 
				$$array[6]		= 	""; 
				$$array[7]		= 	""; 
				$$array[8]		= 	""; 
				$$array[9]		= 	""; 
				$$array[10]		= 	"no";
				$$array[11]		= 	"no";
				
				$oldimagepath 	= "";
		 
			
			$snipp2		=	"";
			$snipp2		.=	"<form method=\"POST\" enctype=\"multipart/form-data\">";
			$snipp2		.=	"<table cellpadding=\"0\" cellspacing=\"10\" border=\"0\" width=\"100%\">";
								
			$snipp2		.=	"<tr>
								<td colspan=\"2\">
									<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">											 
										<tr>
											<td valign=\"middle\">".$mform->label('publisheddate','Publish Date :','caption','req')."</td>
											<td>".$mform->inputBox($task,'text',$array[0],$$array[0],'iputdate input',$dates,'5','readonly')."&nbsp;".$mform->button($task,$triggers,'calbutton')."</td>
											<td valign=\"middle\">".$mform->label('publishedtime','Publish Time :','caption','req')."</td>
											<td>".$mform->inputBox($task,'text',$array[1],$$array[1],'iputdate input','','8','')."</td>
										</tr>
									</table>
								</td>
							</tr>							
							<tr><td colspan=\"2\"><hr/></td></tr>";	
								
															
			$snipp2 		.=	"<tr><td valign=\"top\">".$mform->label('category','Category','caption','req')."</td>
									<td valign=\"top\">
										<select name=\"".$array[2]."\" class=\"iselect cat\">
											<option value=\"0\">- Select One -</option>";
											$query = "SELECT * FROM itemcategory WHERE parent='0' AND status='published' ";
											$snipp2	.=	  $mform->test($query,$$array[2],"caption","id");
							$snipp2	.=	"</select>
									</td>							 
								</tr>";
						 
					$snipp2	.=	"<tr>
									<td valign=\"top\">".$mform->label('subcategory','Sub-Category','caption')."</td>
									<td valign=\"top\">";									 
									$snipp2	.=	"<select name=\"".$array[3]."\" class=\"sub iselect\" disabled>
												</select>";
								 $snipp2	.=	"</td>
								</tr>						
								<tr>
									<td valign=\"top\">".$mform->label('subsubcategory','Sub-Sub-Category','caption')."</td>
									<td valign=\"top\">										 
												<select name=\"".$array[4]."\" id=\"subsubcatid\" class=\"subsub iselect\" disabled>										 
												</select>
									 </td>
								</tr>
								<tr>
									<td valign=\"top\">".$mform->label('brands','Brands','caption')."</td>
									<td valign=\"top\">
										<select name=\"".$array[5]."\" class=\"iselect\">
													<option value=\"0\">- Select One -</option>";
											 $query = "SELECT * FROM brands WHERE brands!='-'";
											 $snipp2	.=	  $mform->test($query,$$array[5],"brands","id");
							$snipp2	.=	"</select>
									 </td>							 
								</tr>";
						
					$snipp2	.=	"<tr>
									<td valign=\"top\">".$mform->label('prodimage','Product Image','caption')."</td>
									<td><input type=\"file\" name=\"".$array[6]."\" id=\"".$array[6]."\"  onChange=\"changeImageCaption2()\" />&nbsp;<input type=\"text\" readonly id=\"".$array[6]."caption\" class=\"changecap\"></td>
								</tr>						
								<tr>
									<td valign=\"top\">".$mform->label('prodname','Product Name','caption','req')."</td>
									<td>".$mform->inputBox($task,'text',$array[7],"",'input iputsize width500','itemSDesc','200')."</td>
								</tr>						
								<tr>
									<td valign=\"top\">".$mform->label('prodsize','Product Size','caption')."</td>
									<td>".$mform->inputBox($task,'text',$array[8],"",'input iputsize width500','itemSize','50')."</td>
								</tr>						
								<tr>
									<td valign=\"top\">".$mform->label('proddesc','Product Description','caption')."</td>
									<td valign=\"top\">".$mform->textarea($task,$array[9],"",'10','10','tinymce txtarea',$$array[9])."</td>
								</tr>";
						$is_featuredcheck = "";
						$is_newcheck = "";
								//$is_featuredcheck	=	($isfeatured == "yes") 	? "checked" : ""; 
								//$is_newcheck		=	($isnew == "yes") 		? "checked" : ""; 
								
					$snipp2	.=	"<tr><td>".$mform->label('newprod','New Product','caption')."</td><td><input type=\"checkbox\" name=\"".$array[10]."\" value=\"yes\"  $is_newcheck /></td></tr>";
					$snipp2	.=	"<tr><td>".$mform->label('featuredprod','Featured Product','caption')."</td><td><input type=\"checkbox\" name=\"".$array[11]."\"  $is_featuredcheck value=\"yes\"/></td></tr>";
					$snipp2	.=	"<tr><td colspan=\"2\">&nbsp;</td></tr>";
					$snipp2	.=	"<tr>
									<td align=\"left\"><input type=\"submit\" name=\"publish$type\" value=\"Publish\" class=\"button2 roundbuttons\"></td>
									<td align=\"right\"><input type=\"submit\" name=\"savedraft$type\" value=\"Save Draft\" class=\"button2 roundbuttons\"></td>
								</tr>
					</table>";
					$snipp2	.=	"<div class=\"message\"><p>";
					 if(!empty($errmsg)){ foreach($errmsg as $errValue){ echo $errValue; } }
					 $snipp2	.=	"</p></div>
			</form>";
			
			
			 $snipp2	.=	"<script type=\"text/javascript\" > 
						function changeImageCaption2(){	 
									var test5	= document.getElementById('".$array[6]."').value;
									document.getElementById('".$array[6]."caption').value = test5;
						} 		
				</script>	";  
				
				
				
			
$snipp2 .= "<script type=\"text/javascript\">
   Calendar.setup(
					{
					  inputField  : \"$dates\",  		// ID of the input field
					  ifFormat    : \"%m/%d/%Y\",      // the date format
					  button      : \"$triggers\"       // ID of the button
					}
				  ); 
</script>";				  
				
				
 $snipp2	.=	"<script type=\"text/javascript\"> 
	$(document).ready(function()
	{
		$(\".cat\").change(function()
		{
			$(\".sub\").removeAttr('disabled');			
			var id				=	$(this).val();
			var dataString 		= 	'id='+ id;
			$.ajax
			({
				type: \"POST\",
				url: 'modules/products/home_ajax_itemcategory.php',
				data: dataString,
				cache: false,
				success: function(html){
					$(\".sub\").html(html);
					$(\".sub\").show();									
					$(\".subedit\").html(html);					
					$(\".subedit\").show();					
					$(\".subsub\").html(html);
					$(\".subsubedit\").html(html);			
				} 
			}); 
		});
				
		$(\".sub\").change(function()
		{
			$(\".subsub\").removeAttr('disabled');
			var id2			=	$(this).val();
			var dataString 	= 	'id2='+ id2;
			$.ajax
			({
				type: \"POST\",
				url: \"modules/products/home_ajax_itemcategory.php\",
				data: dataString,
				cache: false,
				success: function(html)
				{
					$(\".subsub\").html(html);
					$(\".subsub\").show();
				} 
			});
		});
		
		$(\".subedit\").change(function()
		{
			var id2			=	$(this).val();
			var dataString 	= 	'id2='+ id2;
			$.ajax
			({
				type: \"POST\",
				url: \"modules/products/home_ajax_itemcategory.php\",
				data: dataString,
				cache: false,
				success: function(html)
				{
					$(\".subsubedit\").html(html);
					$(\".subsubedit\").show();
				} 
			});
		});	
		
	});
</script>";			 	
			
			//include_once('view/template1/home_tinymceconfig.php'); 
			return $snipp2;
			
	}
	
	
	
	public function productPlugModel(){
	 
		$currentdate 		= 	date('m/d/Y');
		$currenttime 		= 	date('H:i:s');
		$coreMaintenance 	=	new coreMaintenance();
		$type				=	self::getPlugType();
		$array 				=	self::getPlugArray();
		$dbTable2			=	self::getPlugTable();
		$admin				=	$_SESSION['gp_username'];
		$datetime			=	date("Y-m-d H:i:s");  
		$errmsg 			= 	array();
		$savedraft 			= 	"savedraft$type";
		$publish 			= 	"publish$type";
		$oldimagepath 		=	"";
		
		if (isset($_POST[$savedraft]) || isset($_POST[$publish])) {					
					$actionbutton 						=	(isset($_POST[$savedraft])) ? "draft" : "published";
					$prodid 							=	getIDCustom($dbTable2,'itemId');
					$productArr['itemId'] 				=	$prodid;
					$productArr['itemCode']				=	$prodid;
					$productArr['seqno']				=	$prodid; 				 
					$catid 								= 	$array[2];			 
					$productArr['catid'] 				=	(isset($_POST[$catid]) && (!empty($_POST[$catid]))) ? trim($_POST[$catid]) : 0;					
					$subcatid 							= 	$array[3];
					$productArr['subcatid'] 			=	(isset($_POST[$subcatid]) && (!empty($_POST[$subcatid]))) ? trim($_POST[$subcatid]) : 0;					
					$subsubcatid 						= 	$array[4];
					$productArr['subsubcatid'] 			=	(isset($_POST[$subsubcatid]) && (!empty($_POST[$subsubcatid]))) ? trim($_POST[$subsubcatid]) : 0;					 
					$brandid		 					= 	$array[5];
					$productArr['brandid']				=	(isset($_POST[$brandid]) && (!empty($_POST[$brandid]))) ? trim($_POST[$brandid]) : 0;					
					$imagepath	 						= 	$array[6];
					//$productArr['imagepath'] 				=   $coreMaintenance->setImage("yes",$imagepath,'create','products',$oldimagepath);
					$productImage			 			=   $coreMaintenance->setImage("yes",$imagepath,'create','products',$oldimagepath);
					
					if($productImage!==false){
						$productArr['imagepath'] 		=	$productImage;
					}
					
					$itemSDesc							= 	$array[7];
					$productArr['itemSDesc'] 			=	(isset($_POST[$itemSDesc]) && (!empty($_POST[$itemSDesc]))) ? htmlentities(trim($_POST[$itemSDesc])) : "";					
					$itemSize							= 	$array[8];
					$productArr['itemSize'] 			=	(isset($_POST[$itemSize]) && (!empty($_POST[$itemSize]))) ? htmlentities(trim($_POST[$itemSize])) : "";					
					$itemLDesc							= 	$array[9];
					$productArr['itemLDesc'] 			=	(isset($_POST[$itemLDesc]) && (!empty($_POST[$itemLDesc]))) ? htmlentities(trim($_POST[$itemLDesc])) : "";										
					$isnew								= 	$array[10];
					$productArr['isnew'] 				=	(isset($_POST[$isnew]) && (!empty($_POST[$isnew]))) ? htmlentities(trim($_POST[$isnew])) : "no";					
					$isfeatured							= 	$array[11];
					$productArr['isfeatured'] 			=	(isset($_POST[$isfeatured]) && (!empty($_POST[$isfeatured]))) ? htmlentities(trim($_POST[$isfeatured])) : "no";
					$productArr['status'] 				=	$actionbutton;
 					
				/****************/	
				
					$publisheddate 					=	$array[0];
					$publishedtime 					=	$array[1];
					$ppublisheddate					=	(isset($_POST[$publisheddate]) && (!empty($_POST[$publisheddate]))) ? $_POST[$publisheddate] : $currentdate;					
					$spublisheddate					=	$coreMaintenance->saveDateToDb("/",$ppublisheddate);
					$productArr['publisheddate'] 	=	$spublisheddate;
					$publisheddate					=	$ppublisheddate;
					$productArr['publishedtime'] 	=	(isset($_POST[$publishedtime]) && (!empty($_POST[$publishedtime]))) ? $_POST[$publishedtime] : $currenttime;
					$productArr['publishedby']		=	$admin;
					$publishedtime	 				=	$productArr['publishedtime'];
								  
					$productArr['enteredby'] 		=	$admin;
					$productArr['entereddate'] 		=	$datetime;
					$productArr['updateby'] 		=	$admin;
					$productArr['updatedate']		=	$datetime;					  
															
					$error_messages = implode('', $errmsg);
					if (empty($error_messages)) {
						$queryResult = self::insertRecord($productArr, $dbTable2);
						if (empty($queryResult)) {						
							//resize prod pic
							if($productImage!==false)
							$imagepic 	= $productArr['imagepath'];
							
							if(!empty($imagepic)){	
								$thumb 		= PhpThumbFactory::create("./../uploads/products/large/$imagepic");
								$thumb->resize(120);
								$thumb->save("./../uploads/products/resize/$imagepic");
							}						
							
							$prodName 	=	$productArr['itemSDesc'];
							$caption 	=	(empty($prodName)) ? $type : $prodName;
							$activity 	=	"create $caption";
							self::logAdminTask($admin,$type,"create",$activity);							
							header("location:index.php");
						}
					}    
		}
	}	
	
	//retrieve dashboard page plugin
	public function retrievePagePlugin(){
			$result				=	"";
			$modType			=	self::getModType();
			$modId 				=	self::getModId();
			$caption			=	self::getPageCaption();			 
	 		$currentModuleId 	= 	self::getCurrentModAccess();
			
			$plugTable		 	= 	self::getPlugTable();
			
			if(in_array($modId,$currentModuleId)){	
				$result 		 .= "<dt><a href=\"#\">Create $caption</a></dt>";	
				$result 		 .= "<dd>";	
				$moduleType 	  = $modType;
				$newsArr		  = array($moduleType.'publishdate',$moduleType.'publishtime',$moduleType.'images5',$moduleType.'title',$moduleType.'contents',$moduleType.'postingdate',$moduleType.'author',$moduleType.'catid');
				$result 		 .= self::pagePlugin($moduleType,$newsArr,$plugTable,"$moduleType.trigger","$moduleType.date","$moduleType.trigger2","$moduleType.date2");
				$result 		 .= "</dd>";  
			 }
			 return $result;
	}
	
	//retrieve dashboard product plugin
	public function retrieveProductPlugin(){
			$result				=	"";
			$modType			=	self::getModType();
			$modId 				=	self::getModId();
			$caption			=	self::getPageCaption();			 
			$currentModuleId 	= 	self::getCurrentModAccess();
			$plugTable		 	= 	self::getPlugTable();
	
			if(in_array($modId,$currentModuleId)){
				 $result 		  .= "<dt><a href=\"#\">Create $caption</a></dt>";	
				 $result 		  .= "<dd>";	
				 $moduleTypes 	   = $modType; 
				 $productsArr	   = array($moduleTypes.'publishdate',$moduleTypes.'publishtime',$moduleTypes.'catid',$moduleTypes.'subcatid',$moduleTypes.'subsubcatid',$moduleTypes.'brandid',$moduleTypes.'imagepath',$moduleTypes.'itemSDesc',$moduleTypes.'itemSize',$moduleTypes.'itemLDesc',$moduleTypes.'isnew',$moduleTypes.'isfeatured');
				 $result 		  .= self::productPlugin($moduleTypes,$productsArr,$plugTable,"$moduleTypes.trigger3","$moduleTypes.date3");
				 $result 		  .= "</dd>"; 
			}
			return $result;
	}
	
	//retrieve dashboard link plugin
	public function retrieveLinkPlugin(){	
			$result				=	"";
			$modType			=	self::getModType();
			$modId 				=	self::getModId();
			$caption			=	self::getPageCaption();
			$url				=	self::getPageLink();
			$currentModuleId 	= 	self::getCurrentModAccess();
			
		if(in_array($modId,$currentModuleId)){
			$result .= self::setPageCaption($caption);
			$result .= self::setPageLink($url);
			$pageLink = self::getPageLink();
			$result .= "<dt><a href=\"".$pageLink."\">Create ".self::getPageCaption()."</a></dt>";	 
		}
		return $result;
	}
	
	public function selectDashboard($dashType){
		$result = "";
		
		switch($dashType){
			case "page":
				$result	=	self::retrievePagePlugin();
			break;
			
			case "product":
				$result	=	self::retrieveProductPlugin();
			break;
			
			case "link":
				$result =	self::retrieveLinkPlugin();
			break;
		}
			return $result;
	}
	
	
	public function retrieveDashBoard(){
		$dashboard 	= "";
		$dashboard .= "<dl>"; 
		$arrayValues 	=	array('dtype','caption','url','modtype','modid','dtable');
		$retArray 		=	self::retrieveEntry("dashboard", $arrayValues, "", "id!='0' ORDER BY sortid");
		
		foreach ($retArray as $retIndex => $retValue) {
            $$retIndex 	= 	$retValue;
            $mainArr 	= 	explode('|', $$retIndex);
            
            $dtype		= 	$mainArr[0];
            $caption	= 	$mainArr[1];
            $url		= 	$mainArr[2];
            $modtype	= 	$mainArr[3];
            $modid		= 	$mainArr[4];
            $dtable		= 	$mainArr[5];
		 
			self::setModType($modtype);
			self::setModId($modid);
			self::setPageCaption($caption);
			self::setPageLink($url);
			self::setPlugTable($dtable);
$dashboard .= self::selectDashboard($dtype);
		} 
		$dashboard .= "</dl>";
		return $dashboard;
	}
	
	
	
	
}
?>