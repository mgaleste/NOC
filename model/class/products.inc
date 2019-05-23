<?php
class products extends users{
	
	private $productArray;
	private $productId;
	private $productTable;
	private $arrayColumn;
	private $excludeColumn;
	private $tableId;
	private $modType;
	private $mod;
	private $orderVal;
	private $searchVal;
	private $searchArray;
	private $catUrl;
	private $brandUrl;
	private $prodUrl;	
	private $catUrl2;
	private $brandUrl2;
	private $prodUrl2;
	
	
	//Get brand caption
	public function brandCaption($brandid){		
				$brands				=	"";
				$arrayValues2 		=	array('brands');
				$retArray2 			=	self::retrieveEntry("brands", $arrayValues2, "", "id='$brandid' AND status='published' AND type='brands'");
				foreach ($retArray2 as $retIndex => $retValue){
					$$retIndex 		=	$retValue;
					$mainArr 		=	explode('|', $$retIndex);
					$brands		 	=	$mainArr[0];										
				}				
				return $brands;
	}
	//Get Product Category caption
	public function prodCatCaption($catid){		
				$cat 				= 	"";
				$arrayValues2 		=	array('caption');
				$retArray2 			=	self::retrieveEntry("itemcategory", $arrayValues2, "", "id='$catid' AND status='published'");
				foreach ($retArray2 as $retIndex => $retValue){
					$$retIndex 		=	$retValue;
					$mainArr 		=	explode('|', $$retIndex);
					$cat		 	=	$mainArr[0];										
				}				
				return $cat;
	}
	
	//Retrieve per product entry	
	public function productRetrieveInfo(){
		$productId				=	self::getUserId();
		$productTable			=	self::getUserTable();
		$joinTable				=	self::getJoinTable();
		$productArray			=	self::getUserArray(); //single dimension array output		
		$productCond			=	self::getUserCond();		
		$retArray				=	self::retrieveEntry($productTable, $productArray, $joinTable, $productCond);
		foreach ($retArray as $retIndex => $retValue) {
            $$retIndex 			= 	$retValue;
            $mainArr 			=	explode('|', $$retIndex);
			$returnValue  		= 	array();			 	 
			foreach ($mainArr as $mainArrIndex => $mainArrValue) {					
				$returnValue[] 	= 	$mainArrValue;			
			}			         
        }
		return $returnValue;
	}	
	
	//Retrieve All product entry	
	public function productRetrieveAll(){
		$productId			=	self::getUserId();
		$productTable		=	self::getUserTable();
		$joinTable			=	self::getJoinTable();
		$productArray		=	self::getUserArray(); //multi dimension array output		
		$dbFieldArray		=	array();
		foreach($productArray as $prodIndex => $prodVal){
			$dbFieldArray[]	=	$productArray[$prodIndex][0]; //Get db fields only from multi dimension array
		}		
		$productCond		=	self::getUserCond();		
		$retArray			=	self::retrieveEntry($productTable, $dbFieldArray, $joinTable,$productCond);
		$returnValue  		= 	array();			 	 
		foreach ($retArray as $retIndex => $retValue) {
            $$retIndex 		= 	$retValue;
            $mainArr 		=	explode('|', $$retIndex);			
			$returnValue[] 	= 	$mainArr;						 
        }
		return $returnValue;
	}	 
	
	//Show Header Options
	public function showHeaderOptions(){
		$mod					=	"";
		$type					=	"";		
		$theaderOptions			=	"";			
		$headerArray			=	self::getUserHeader();			
		$type					=	self::getModuleType();			
		$mod					=	self::getModule();					 
		$excludeArray			=	self::getExcludeArray();				
		$counter 				= 	(empty($excludeArray)) ? count($headerArray) + 1 : count($headerArray);
		$core					=	new coreMaintenance();
		$createButton			= 	$core->createTaskButton($mod,$type);
		
		$theaderOptions			.=	"<tr>
										<th colspan=\"$counter\">
													<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\" height=\"40px\">
														<tr>						
															<td class=\"font10 normal\">$createButton</td>
															<td class=\"font10 normal\">
																<input style=\"border:none\" type=\"image\" src=\"".MODIMAGES."delete.gif\" name=\"submit_delete\" width=\"16\" height=\"16\"><button name=\"submit_delete\" class=\"delbut\">Delete</button>[<input name=\"hidden_selected\" class=\"verdana10 input_hidden\" type=\"text\" id=\"removeChecked\" value=\"0\">]<button name=\"submit_delete\"  class=\"delbut\">".ucfirst($type)."(s)</button>
															</td>
															<td class=\"font10 normal\">
															<a href=\"index.php?mod=products&type=products_category\"><img src=\"".MODIMAGES."list.gif\" border=\"0\" alt=\"\">&nbsp;&nbsp;Product Category(s)</a>
															</td>";
							$theaderOptions			.=		"<td class=\"font10 normal\">
															<a href=\"index.php?mod=products&type=brands\"><img src=\"".MODIMAGES."list.gif\" border=\"0\" alt=\"\">&nbsp;&nbsp;Product Brand(s)</a>
															</td>";
							$theaderOptions			.=		"<td  align=\"right\" class=\"verdana10 normal\">";
							$theaderOptions			.=	self::showItems();												
							$theaderOptions			.=	"	</td>
														</tr>
													</table>
										</th>
									</tr> ";
					
					return $theaderOptions;
	}
	
	//Show Filter
	public function tableFilter(){
	
		$filter	=	"";
		$filter	.=	"<select class=\"width200\">";
		$filter	.=	"<option>";
		$filter	.=	"</option>";
		$filter	.=	"</select>";		
		$filter	.=	"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	 
		return $filter;	
	}
	
	public function filterButton(){
		$filterButton	=	"";
		$filterButton	.=	"<input type=\"submit\" value=\"Filter\" class=\"roundbuttons button2\">";
		return $filterButton;
	}
	
	//Show Table Header
	public function showTableHeader(){
		$theader				=	"";
		$headerArray			=	self::getUserHeader();	
		$excludeArray			=	self::getExcludeArray();		
		$counter 				= 	(empty($excludeArray)) ? count($headerArray) + 1 : count($headerArray);
		$theader				.=	"<thead>";
		/*$theader				.=	self::tableFilter();
		$theader				.=	self::tableFilter();
		$theader				.=	self::tableFilter();		
		$theader				.=	self::filterButton();*/
		//$theader				.=	"<tr><th class=\"height20\"></th></tr>";
		
		$theader				.= 	self::showHeaderOptions();			
		$theader				.=	"<tr>";	 
		$theader				.=	"<th class=\"width10 table_line1_left\"><input onclick=\"checkAllFields(1);\" id=\"checkAll\" type=\"checkbox\"></th>";
			 $arrayColumn		=	self::getArrayColumn();
			 for($i=0; $i<$arrayColumn; $i++){
				foreach($headerArray as $hIndex => $hValue){					
					$dbField	=	$headerArray[$hIndex][0];	
					$field 		=	$headerArray[$hIndex][1];	
					 
					if (!in_array($dbField, $excludeArray))
					$theader		.=	"<th valign=\"middle\" class=\"".$headerArray[$hIndex][3]."\"><a href=\"".$headerArray[$hIndex][2]."\"><img class=\"arrowicon\" src=\"".MODIMAGES."updown.png\" border=\"0\" alt=\"0\"/>  $field</a></th>";
				}
			 }
		$theader				.=	"</tr>";	
		$theader				.=	"<tr><th class=\"bar\" colspan=\"$counter\"></th></tr>";
		$theader				.=	"</thead>";		
		return $theader;
	} 
	
	
	
	//Show Table Footer
	public function showTableFooter(){			 
			$tfooter 			=	"";
			$query 				=	self::getSqlQuery();	
			$headerArray		=	self::getUserHeader();	
			$excludeArray		=	self::getExcludeArray();		
			$counter 			= 	(empty($excludeArray)) ? count($headerArray) + 1 : count($headerArray);			
			$pagination_array 	=	array();
			$footspace			= 	$counter/2;
			$pagination_array 	=	self::paginationQuery($query);		 
			$tfooter			.=	"<tfoot>";
			$tfooter			.=	"<tr><td class=\"bar\" colspan=\"$counter\"></td></tr>";
			
			$tfooter			.=	"<tr>";	 
			$tfooter			.=	"<td class=\"width10 table_line1_left\">&nbsp;</td>";
			 $arrayColumn		=	self::getArrayColumn();
			 for($i=0; $i<$arrayColumn; $i++){
				foreach($headerArray as $hIndex => $hValue){					
					$dbField	=	$headerArray[$hIndex][0];	
					$field 		=	$headerArray[$hIndex][1];	
					 
					if (!in_array($dbField, $excludeArray))
					$tfooter		.=	"<td valign=\"middle\" class=\"".$headerArray[$hIndex][3]."\"><a href=\"".$headerArray[$hIndex][2]."\"><img class=\"arrowicon\" src=\"".MODIMAGES."updown.png\" border=\"0\" alt=\"0\"/>  $field</a></td>";
				}
			 }
		$tfooter				.=	"</tr>";	
			
			
			$tfooter			.=	"<tr>";			
			$tfooter			.=	"<td class=\"paddleft table_footer_left\" colspan=\"$footspace\">".$pagination_array['PAGINATION_INFO']."</td>";
			$tfooter			.=	"<td class=\"paddleft table_footer_left_right\" align=\"right\" colspan=\"$footspace\">".$pagination_array['PAGINATION_LINKS']."</td>";
			$tfooter			.=	"</tr>"; 
			
			$tfooter			.=	"</tfoot>";
			return $tfooter;
	}
	
	public function showTableBody(){
		$tbody				=	"";
		$tbody				.=	"<tbody>";
		$contentArray		=	self::getUserHeader();	
		$query 				=	self::getSqlQuery();		
		$pagination_array 	=	array();
		$pagination_array 	=	self::paginationQuery($query);			
		$num_rows			=	$pagination_array['num_rows'];
		$result				=	$pagination_array['result'];
		$excludeArray		=	self::getExcludeArray();
		$counter 			= 	(empty($excludeArray)) ? count($contentArray) + 1 : count($contentArray);			
			
			if($num_rows>0){
					$bgcolor = "";						 								
					for($i=0;$i<$num_rows;$i++){					 				 			
						$bgcolor = ($bgcolor != "#FFFFFF")? "#FFFFFF" : "#EFEFEF";					
							$tbody			.=	"<tr bgcolor=\"$bgcolor\" >";
							$tableID		=	self::getTableId();		//Show Table Id 
							$ids	 		=	mysql_result($result, $i, $tableID);	//Get Db TableId
 							$tbody			.=	"<td align=\"center\" class=\"width10 table_line2_left\"><input class=\"ibox\" value=\"$ids\" name=\"delAnn[]\" onclick=\"checkAllFields(2);\" type=\"checkbox\"></td>";
							$mod			=	self::getModule();
							$type			=	self::getModuleType();
									
						foreach($contentArray as $hIndex => $hValue){												
							$field 			=	$contentArray[$hIndex][0];	//Get Db Fields
							$contentcss		=	$contentArray[$hIndex][4];	//Get Content CSS
							$editable		=	$contentArray[$hIndex][5];	//Get Content Editable
							$editlink		=	$contentArray[$hIndex][6];	//Get Content Editable Link
							$$field 		=	mysql_result($result, $i, $field);	//Get Db Field Values							
							
						//task listing	
							$servername         = $_SERVER['SERVER_NAME'];
							$uri 				= substr($_SERVER['REQUEST_URI'], 1);
							$url 				= explode('/',substr($_SERVER['REQUEST_URI'], 1));
							$site 				= $url[0];						 
							if($site=='apanel'){
								$view_url		= "http://$servername/index.php?mod=$type&id=$ids&previewonly=true";
							}else{
								$view_url		= "http://$servername/$site/index.php?mod=$type&id=$ids&previewonly=true";
							}							
							$greybox_url		='<a href="'.$view_url.'" title="Preview" rel="gb_page[1000, 500]" class="none" title="View this event">View</a>';
							$inner_pane			= "<div class=\"none\" style=\"padding-top:5px; display:none\" id=\"{$ids}\"><font face=\"verdana\" size=\"2\"><a href=\"index.php?mod=$mod&type=$type&task=edit&sid=$ids\" class=\"none\" title=\"Edit this page\">Edit </a>| <a href=\"index.php?mod=$mod&type=$type&task=delete&sid=$ids\" onclick=\"return confirm('Are you sure you want to delete?');\" class=\"none\" title=\"Delete this page\">Delete </a>| $greybox_url </font></div>";						
							
							if($$field=='itemLDesc')
							$contents		= 	(empty($$field)) ? "&nbsp;" : limit_words(20,stripslashes(html_entity_decode($$field)));
							$contents		= 	(empty($$field)) ? "&nbsp;" : limit_words(20,stripslashes(html_entity_decode($$field)));
							$fieldcontent	= 	(!empty($editable)) ? "<a href=\"$editlink$ids\">$contents</a>$inner_pane" : $contents;
							$hover			= 	(!empty($editable)) ? 'onmouseover="show_div(\''.$ids.'\')" onmouseout="hide_div(\''.$ids.'\')"' : '';
						 
							if (!in_array($field, $excludeArray)) //Hide Table Column 
							$tbody			.=	"<td valign=\"top\" class=\"$contentcss\" $hover>".stripslashes(html_entity_decode($fieldcontent))."</td>";
						}
					$tbody					.=	"</tr>";							 
					}
			}else{
					$tbody					.=	"<tr>";
					$tbody					.=	"<td colspan=\"$counter\" class=\"center req height40\">- No Records Found -</td>";
					$tbody					.=	"</tr>";
			}
		$tbody				.=	"</tbody>";
		return $tbody;
	}
	
	//Output Table Listing
	public function showTable(){
			$productHTML 			= 	"";
			$productsRetrieval 		=	self::productRetrieveAll();								 			
			$productHTML			.= 	"<form method=\"POST\"  onsubmit=\"return ValidateForm(this, 'delAnn[]');\">";
			$productHTML			.= 	"<table  border=\"0\" class=\"tablelist\" cellpadding=\"0\" cellspacing=\"0\">";
			$productHTML			.= 	self::showTableHeader();			
			$productHTML			.= 	self::showTableFooter();			
			$productHTML			.= 	self::showTableBody();			
			$productHTML			.= 	"</table>";	
			$productHTML			.= 	"</form>";	
			echo $productHTML; 
			return $productHTML;
	}
	
	//Show Items in Table Listing
	public function showItems(){
		$displayItems 	=	"";
		$items			=	self::getItems();		
		$searchItem		=	self::getSearchVal();		
		$catUrl			=	self::getCatUrl();
		$brandUrl		=	self::getBrandUrl();	 	
		$prodUrl		=	self::getProdUrl();	 		
		$searchCond		=	(empty($searchItem)) ? "" : "&search=$searchItem";
		$mform 			=	new formMaintenance();		 
		$query 			=	"SELECT name FROM reference WHERE ref_name='display_items'";
		$mod			=	self::getModule();
		$type			=	self::getModuleType();
		$displayItems 	.=	"Item Count	:  <select onchange=\"location.href='?mod=$mod&type=$type$searchCond$catUrl$brandUrl$prodUrl&items='+this.value\">";
		$displayItems 	.=	$mform->select2($query,$items,"name","name");					
		$displayItems 	.=	"</select>";	 
		return $displayItems;	
	}
	
	public function showItems2(){
		$displayItems 	=	"";
		$items			=	self::getItems();		
		$searchItem		=	self::getSearchVal();		
		$catUrl			=	self::getCatUrl();
		$brandUrl		=	self::getBrandUrl();	 	
		$prodUrl		=	self::getProdUrl();	 		
		$searchCond		=	(empty($searchItem)) ? "" : "&search=$searchItem";
		$mform 			=	new formMaintenance();		 
		$query 			=	"SELECT name FROM reference WHERE ref_name='display_items'";
		$mod			=	self::getModule();
		$type			=	self::getModuleType();
		$displayItems 	.=	"Item Count	:  <select id=\"itemcount\">";
		$displayItems 	.=	$mform->select2($query,$items,"name","name");					
		$displayItems 	.=	"</select>";	 
		return $displayItems;	
	}
	
	public function showItems3(){
		$displayItems 	=	"";
		$items			=	self::getItems();		
		$searchItem		=	self::getSearchVal();		
		$catUrl			=	self::getCatUrl();
		$brandUrl		=	self::getBrandUrl();	 	
		$prodUrl		=	self::getProdUrl();	 		
		$searchCond		=	(empty($searchItem)) ? "" : "&search=$searchItem";
		$mform 			=	new formMaintenance();		 
		$query 			=	"SELECT name FROM reference WHERE ref_name='display_items'";
		$mod			=	self::getModule();
		$type			=	self::getModuleType();
		$displayItems 	.=	"Item Count	:  <select onchange=\"location.href='?mod=$mod&type=$type$searchCond$catUrl$brandUrl$prodUrl&items='+this.value\">";
		$displayItems 	.=	$mform->select2($query,$items,"name","name");					
		$displayItems 	.=	"</select>";	 
		return $displayItems;	
	}
	
	//Set Default Item Table Listing
	public function defaultItemList(){			 
			$arrayValues 		=	array('name');		
			self::setUserTable("reference");			
			self::setUserCond("remarks1='default' AND ref_name='display_items'");   			
			self::setUserArray($arrayValues);    
			$default 			=	self::productRetrieveInfo();					
			foreach($arrayValues as $aIndex => $aValue){ 
				$$aValue		=	$default[$aIndex];
			}
		 return $$aValue;	
	}
	
	//Single Sorting
	public function orderValue(){
		$orderval = self::getOrderVal();
			if($orderval == "ASC") {
				$orderval = "DESC";
			}else if ($orderval == "DESC") {
				$orderval = "ASC";
			}
		return $orderval;	
	}
	
	//Search
	public function searchValue(){
			$searchcond		=	"";
			$searchItem 	=	self::getSearchVal();
			$sitem	=	htmlentities($searchItem);
			$searchArray 	= 	self::getSearchArray();
		foreach($searchArray as $searchIndex => $searchVal){
			$searchcond		.=	$searchVal . " LIKE '%$sitem%' OR ";
		}
		$searchresult 		= "AND (" . substr($searchcond, 0, -3) . ") ";	
		return $searchresult;
	}
	 
	public function setCatUrl($catUrl){
		$this->catUrl = $catUrl;
	}
	
	public function getCatUrl(){
		return $this->catUrl;
	}
	
	public function setCatUrl2($catUrl2){
		$this->catUrl2 = $catUrl2;
	}
	
	public function getCatUrl2(){
		return $this->catUrl2;
	}
	
	public function setBrandUrl($brandUrl){
		$this->brandUrl = $brandUrl;
	}
	
	public function getBrandUrl(){
		return $this->brandUrl;
	}
	
	public function setBrandUrl2($brandUrl2){
		$this->brandUrl2 = $brandUrl2;
	}
	
	public function getBrandUrl2(){
		return $this->brandUrl2;
	}
	
	public function setProdUrl($prodUrl){
		$this->prodUrl = $prodUrl;
	}
	
	public function getProdUrl(){
		return $this->prodUrl;
	}
	
	public function setProdUrl2($prodUrl2){
		$this->prodUrl2 = $prodUrl2;
	}
	
	public function getProdUrl2(){
		return $this->prodUrl2;
	}
	
	public function setArrayColumn($arrayColumn){
		$this->arrayColumn = $arrayColumn;
	}
	
	public function getArrayColumn(){
		return $this->arrayColumn;
	}
	
	public function setExcludeArray($excludeColumn){
		$this->excludeColumn = $excludeColumn;
	}
	
	public function getExcludeArray(){
		return $this->excludeColumn;
	}
	
	public function setTableId($tableId){
		$this->tableId = $tableId;
	}
	
	public function getTableId(){
		return $this->tableId;
	}
	
	public function setModule($mod){
		$this->mod = $mod;
	}
	
	public function getModule(){
		return $this->mod;
	}
	
	public function setModuleType($modType){
		$this->modType = $modType;
	}
	
	public function getModuleType(){
		return $this->modType;
	}
	
	public function setOrderVal($orderVal){
		$this->orderVal = $orderVal;
	}
	
	public function getOrderVal(){
		return $this->orderVal;
	}
	
	public function setSearchVal($searchVal){
		$this->searchVal = $searchVal;
	}
	
	public function getSearchVal(){
		return $this->searchVal;
	}
	
	public function setSearchArray($searchArray){
		$this->searchArray = $searchArray;
	}
	
	public function getSearchArray(){
		return $this->searchArray;
	}	 
}
?>