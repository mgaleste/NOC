<?php 
	ob_start();
	include_once("../site.php");
	include_once("../model/advanced_functions.php");
	include_once("../model/functions.php");

	$datetime	= 	date("Y-m-d H:i:s");	
	$modname	=  	isset($_GET['mod']) ? $_GET['mod'] : "";						
 						
	$sid		=  	isset($_GET['sid']) ? $_GET['sid'] : "";
	$task 		=  	isset($_GET['task']) ? $_GET['task'] :"";	
	$type 		=  	isset($_GET['type']) ? $_GET['type'] :"";												
	$addquery 	= "";
	$mod 		= "";									

	$modulerecord			=	new record('category');  
	$moduletask 			=  	isset($_GET['task']) ? $_GET['task'] : "";	
	$errmsg		 			=  	isset($_GET['errmsg']) ? $_GET['errmsg'] : "";		

	
	$recon 					=	new recordUpdate();
	$mform					= 	new formMaintenance();	
	$validation_class		=	new validations();		
	$imgfunc 				= 	new imageFunctions();		

	$key = "" ;

if(isset($_POST['reset']))
{
	header("Location: popups/category.php");
}

if(isset($_POST['key']))
{
	if(strlen($_POST['key'])>0)
	{
		header("Location: popups/category.php?key=".$_POST['key']);
	}
}
	 
?> 


<?

if($task=='edit'){
	//RETRIEVE CATEGORY
     $arrayValues4 	= array('id', 'category','parent');
     $retArray4 = $recon->retrieveEntry("category", $arrayValues4, "", " id='$sid' and type='$type'");
     foreach ($retArray4 as $retIndex4 => $retValue4) {
        $$retIndex4 = $retValue4;
        $mainArr4   = explode('|', $$retIndex4);
        $sid        = $mainArr4[0];
        $category = $mainArr4[1];        
        $catid = $mainArr4[2];
    }
}else{
	$category = "";
	$catid= "";	 
}
 

	if(isset($_POST['Submit'])){
	
		$catid 		= (isset($_POST['catid']) 	&& (!empty($_POST['catid'])))		? $_POST['catid'] : 0;
		$catname 	= addslashes(htmlentities($_POST['catname']));		
		$arr = array();	
if($task!='edit'){		
		$arr['id'] = getID("category");
}else{
		$arr['id'] = $sid;
}		
		$arr['category'] = $catname;
		$arr['type'] = $type;
		$arr['parent'] = $catid;
		$arr['permalink'] = str_replace(" ","-",$catname);
		
	 
		if($task=='edit'){
			$recon->updateRecord($arr,"category","id='$sid' and type='$type'");			
		}else{
			$recon->insertRecord($arr,"category");
		}

		header("location:category.php?type=$type");
		 
	}

?>



<script>

function loadCategory()
{	
	parent.opener.location.reload();
	window.close();
}
</script>
<link rel="stylesheet" href="../view/template1/css/mystyles.css" type="text/css"/>	
<form method="POST" name="form" enctype="multipart/form-data">	
                <table width="100%" cellpadding="0" cellspacing="5" border="0">
					<tr><td colspan="2"><h1>CATEGORY</h1></td></tr>
					<tr>	
							<td valign="top"><?= $mform->label('parent','Parent :','caption')?></td>							
							<td>
								<select name="catid" id="catid" class="iselect">
									<option value="0">- Select One -</option>
									<? display_child('0','0',$type,$catid); ?>
								</select>
							</td>
					</tr>
					<tr>							
							<td valign="top"><?= $mform->label('category','Category :','caption')?></td>							
							<td><input type="text" name="catname" value="<?=$category?>" style="border:1px solid #c0c0c0; height:30px; width:200px"></td>
					</tr>
					
					
				 
				 
				 
				 <tr><td colspan="2" height="20px"></td></tr>
				
					<tr>
						<td><input type="submit" name="Submit" value="Save" class="button"></td>
						<td><input type="button" name="close" value="Close" class="button" onclick="loadCategory();" ></td>
					</tr>
                </table>
            </form>
			
			
			
<script type="text/javascript" >
	function changeImageCaption(){	 		 
		var test	= document.getElementById('images').value;
		document.getElementById('imagecaption').value = test;
	}
</script>


<!------------------------------------------------------------------>

<?
//DELETE FROM LIST
	if(isset($_POST['hidden_selected'])){	
			if(count($_POST['hidden_selected'])>0){			
					$str_ids = implode(',',$_POST['delAnn']);
					//delete selected 
					$recon->deleteRecord("category","id IN ($str_ids)");
					header("location:category.php?type=$type");
			}		
	}


//LISTING
	    $paginate_arr['paginatequery'] = "SELECT c.id as id, c.category as category, p.category as parent FROM category c LEFT JOIN category p ON p.id=c.parent WHERE c.type='$type' ";
	   $paginate_arr['query'] = "ORDER BY c.category";
	 
	$paginate_arr['addquery'] = "";
	
	 
	//call the function  where you will pass your array of queries for the class' future use
	$modulerecord->manage_record_queries($paginate_arr);
	//get pagination results then assign to $article list
	$list	 				= 	$modulerecord->get_paginated_array(5);
	$result					=	$list['result'];
	$num_rows				=	$list['num_rows'];
	$PAGINATION_LINKS		=	$list['PAGINATION_LINKS'];
	$PAGINATION_INFO		=	$list['PAGINATION_INFO'];
	$PAGINATION_TOTALRECS	=	$list['PAGINATION_TOTALRECS'];
	
 ?>


<form method="POST"  onsubmit="return ValidateForm(this, 'delAnn[]');">
			<table width="100%" cellpadding="0" cellspacing="0">					
					<tr>
						<td colspan="4">
							<table cellpadding="0" cellspacing="0" border="0" width="100%">
								<tr valign="middle">						
									<td class="verdana10">
										<button name="submit_delete" class="delbut">Delete</button>[<input name="hidden_selected" class="verdana10 input_hidden" type="text" id="removeChecked" value="0">]<button name="submit_delete"  class="delbut"></button>							
									</td>
									
								</tr>
							</table>
						</td>
					</tr> 
					<tr><td colspan="6">&nbsp;</td></tr>
					<tr valign="middle">
						<td width="5%" align="center" class="table_line1_left"><input onclick="checkAllFields(1);" id="checkAll" type="checkbox"></td>										
						<td width="25%" class="table_line1_left">CATEGORY</td>							
						<td width="25%" class="table_line1_left_right">PARENT</td>																		 						
					</tr>
					<tr valign="middle"><td class="bar" colspan="3"></td></tr>		
			<?php
				$bgcolor = "";
				if($num_rows>0){	
					for($i=0;$i<$num_rows;$i++){
					
						$ids 				= mysql_result($result, $i, "id");									

						$category	 		= mysql_result($result, $i, "category");										 	
						$parent			 	= mysql_result($result, $i, "parent");	
						

			
				 			
						 
						 
						$servername         = $_SERVER['SERVER_NAME'];
						$uri 				= substr($_SERVER['REQUEST_URI'], 1);
						$url 				= explode('/',substr($_SERVER['REQUEST_URI'], 1));
						
						$site 				= $url[0];
						if($site=='apanel'){
							$view_url			= "http://$servername/index.php?mod=$mod&type=$type&previewonly=true";
						}else{
							$view_url			= "http://$servername/$site/index.php?mod=$mod&type=$type&previewonly=true";
						}
						
						$greybox_url		='<a href="'.$view_url.'" title="Branch Preview" rel="gb_page[1000, 500]" class="none" title="View this event">View</a>';
						  			
						
							
							$bgcolor = ($bgcolor != "#FFFFFF")? "#FFFFFF" : "#EFEFEF"; 							
							echo "<tr bgcolor=\"$bgcolor\"  height=\"55px\" >";								 												
							echo '	<td align="center" class="table_line2_left"><input class="ibox" value="'.mysql_result($result, $i, "id").'" name="delAnn[]" onclick="checkAllFields(2);" type="checkbox"></td>
									<td class="table_line2_left category paddleft" valign="top" align="left"><a href="category.php?type='.$type.'&task=edit&sid='.$ids.'">'.$category.'</a></td>
									<td class="table_line2_left_right category paddleft" valign="top" align="left">'.$parent.'&nbsp;</td>';
							echo '</tr>';
					}
				}else{
					echo '<tr valign="middle" width="20px"><td colspan="3" class="table_line2_left table_line2_left_right" align="center"><p class="errmsg">NO RECORDS FOUND</p></td></tr>';
				}
				echo '<tr valign="middle"><td class="bar" colspan="3"></td></tr>		
					<tr valign="middle" width="18px">
						<td class="table_footer_left" align="center"  >&nbsp;'.$PAGINATION_INFO.'</td>
						<td class="table_footer_left" align="center"  ><span class="link">'.$PAGINATION_LINKS.'</span></td>
						<td class="table_footer_left_right"    align="center">Total Pages: '.$PAGINATION_TOTALRECS.'</td></tr>';		
				?>		
				
				 

			</table>
			</form>
























<script type="text/javascript" src="../plugins/archiving/checkbox_deselectall.js"></script>
<script type="text/javascript" src="../plugins/archiving/checkbox_selectall.js"></script> 
<?php ob_end_flush(); ?>				
