<table width="100%" class="form  paddright" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td align="" class="header"></td>
		<td align="right">	
			<form method="POST">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="4" align="right"><?= $mform->inputBox($task,'text','psearch_entry',"",'search input','psearch_entry','15');?></td>
					<td>&nbsp;<?= $mform->inputBox($task,'submit','doSearch',"Search",'roundbuttons button2','doSearch','5');?></td>
				</tr>
			</table>
			</form>
		</td>
	</tr>		 
	 
	 
	<tr>
		<td colspan="2">
			<form method="POST"  onsubmit="return ValidateForm(this, 'delAnn[]');">
			<table width="100%" cellpadding="0" cellspacing="0" id="myTable" class="tablesorter">					
				<thead>		
					<tr>
						<th colspan="5">
							<table cellpadding="0" cellspacing="0" border="0" width="100%">
								<tr valign="middle">						
									<td class="verdana10"><a href="index.php?mod=<?=$mod?>&type=<?=$type?>&task=create" class="sub_link"><img src="<?=MODIMAGES;?>create.gif" border="0" alt=""> Create New <?=rep_under(ucfirst($type))?></a></td>
									<td class="verdana10"><input style="border:none" type="image" src="<?=MODIMAGES;?>delete.gif" name="submit_delete" width="16" height="16"><button name="submit_delete" class="delbut">Delete</button>[<input name="hidden_selected" class="verdana10 input_hidden" type="text" id="removeChecked" value="0">]<button name="submit_delete"  class="delbut"><?=rep_under(ucfirst($type))?>(s)</button></td>
									<td  align="right" class="verdana10"><?= $userProd->showItems();?></td>
								</tr>
							</table>
						</th>
					</tr> 
					<tr><th colspan="5">&nbsp;</th></tr>
					<tr valign="middle">
						<th width="5%" align="center" class="table_line1_left"><input onclick="checkAllFields(1);" id="checkAll" type="checkbox"></th>
						<th width="10%" class="table_line1_left">Applicant Number</th>
						<th width="25%" class="table_line1_left">Applicant Name</th>
						<th width="5%" class="table_line1_left">Age</th>
						<th width="5%" class="table_line1_left">Sex</th>
						<th width="20%" class="table_line1_left">Destination</th>
						<th width="20%" class="table_line1_left">Position Applied</th>
						<th width="20%" class="table_line1_left">Applicant Type</th>
						<th width="10%" class="table_line1_left_right">Status</th>						
					</tr>
					<tr valign="middle"><th class="bar" colspan="9"></th></tr>		
				</thead>
				
				<tfoot>
					<tr valign="middle"><td class="bar" colspan="9"></td></tr>		
					<tr valign="middle">
						<td width="5%" class="table_line1_left">&nbsp;</td>
						<td width="10%" class="table_line1_left"></td>
						<td width="25%" class="table_line1_left"></td>
						<td width="5%" class="table_line1_left"></td>
						<td width="5%" class="table_line1_left"></td>
						<td width="20%" class="table_line1_left"></td>
						<td width="20%" class="table_line1_left"></td>
						<td width="20%" class="table_line1_left"></td>
						<td width="10%" class="table_line1_left_right"></td>						
					</tr>
					<tr valign="middle">
						<td class="table_footer_left" align="center" colspan="2"><?=$PAGINATION_INFO?></td>
						<td class="table_footer_left_right"   colspan="7"  align="center"><span class="link"><?=$PAGINATION_LINKS?></span></td>
					</tr>					
				</tfoot>
				
				<tbody>		
			<?php
				$bgclass = "";
				if($num_rows>0){
					$paneContent = "";
					for($i=0;$i<$num_rows;$i++){
					
						$ids 				= ($servername!='localhost')? mysql_result($result, $i, "applicantOnlineCode") : mysql_result($result, $i, "applicantCode");
						$lastname		 	= mysql_result($result, $i, "lastName");	
						$firstname		 	= mysql_result($result, $i, "firstName");				
						$middlename		 	= mysql_result($result, $i, "middleName");				
						$systemStatus			 	= ($servername!='localhost')? mysql_result($result, $i, "systemOnlineStatus") : mysql_result($result, $i, "systemStatus");
						
						$age			 	= mysql_result($result, $i, "age");
						$sex			 	= mysql_result($result, $i, "gender");
						$applicantType	 	= mysql_result($result, $i, "applicantType");
						$positionTitle	 	= mysql_result($result, $i, "positionTitle");
						
						$servername         = $_SERVER['SERVER_NAME'];
						$uri 				= substr($_SERVER['REQUEST_URI'], 1);
						$url 				= explode('/',substr($_SERVER['REQUEST_URI'], 1));
						$modtype = $type;
						$site 				= $url[0];
						
						$applicantName		= stripslashes(html_entity_decode($lastname)).", ".stripslashes(html_entity_decode($firstname))." ".stripslashes(html_entity_decode($middlename));
						
						$link				= "index.php?mod=$mod&type=$type&task=view&sid=$ids";
			 
			
						
							
							$bgclass	 = ($bgclass == 'odd_row') ? 'even_row' : 'odd_row';						
							echo "<tr class=\"$bgclass list_row\" id=\"$link\" onClick=\"viewRecord(event,this.id);\">";
							echo '	<td align="center" class="table_line2_"><input class="ibox" value="'.$ids.'" name="delAnn[]" onclick="checkAllFields(2);" type="checkbox"></td>
									<td class="table_line2_left">'.$ids.'</td>								
									<td class="table_line2_left">'.$applicantName.'&nbsp;</td>
									<td class="table_line2_left">'.stripslashes(html_entity_decode($age)).'&nbsp;</td>
									<td class="table_line2_left">'.stripslashes(html_entity_decode($sex)).'&nbsp;</td>
									<td class="table_line2_left">'.stripslashes(html_entity_decode($destination)).'&nbsp;</td>
									<td class="table_line2_left">'.stripslashes(html_entity_decode($positionTitle)).'&nbsp;</td>
									<td class="table_line2_ left">'.stripslashes(html_entity_decode($applicantType)).'&nbsp;</td>
									<td class="table_line2_left_right">'.stripslashes(html_entity_decode($systemStatus)).'&nbsp;</td>';
							echo '</tr>';
					}
				}else{
					echo '<tr valign="middle" width="20px"><td colspan="9" class="table_line2_ table_line2__right" align="center"><p class="errmsg">NO RECORDS FOUND</p></td></tr>';
				}
				?>		
				</tbody>		
			</table>
			<div class="message"><p><?= (!empty($_GET['errmsg'])) ? $_GET['errmsg'] : ""; ?></p></div>
			</form>
		</td>
	</tr>
</table>


<? include_once('view/template1/graybox.php');?>		
<? include_once('view/template1/message.php');?>	


