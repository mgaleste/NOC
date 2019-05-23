 
<table width="100%" class="form paddleft paddright" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td align="left" class="header"></td>
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
			<table width="100%" cellpadding="0" cellspacing="0">					
					<tr>
						<td colspan="3">
							<table cellpadding="0" cellspacing="0" border="0" width="100%">
								<tr valign="middle">						
									<td class="verdana10"><a href="index.php?mod=<?=$mod?>&type=<?=$type?>&task=create" class="sub_link"><img src="<?=MODIMAGES;?>create.gif" border="0" alt=""> Create New <?=rep_under(ucfirst($type))?></a></td>
									<td class="verdana10">
										<input style="border:none" type="image" src="<?=MODIMAGES;?>delete.gif" name="submit_delete" width="16" height="16"><button name="submit_delete" class="delbut">Delete</button>[<input name="hidden_selected" class="verdana10 input_hidden" type="text" id="removeChecked" value="0">]<button name="submit_delete"  class="delbut"><?=rep_under(ucfirst($type))?>(s)</button>							
									</td>
									<td  align="right" class="verdana10"><?= $userGroupsProd->showItems();?></td>
								</tr>
							</table>
						</td>
					</tr> 
					<tr><td colspan="3">&nbsp;</td></tr>
					<tr valign="middle">
						<td width="5%" align="center" class="table_line1_left"><input onclick="checkAllFields(1);" id="checkAll" type="checkbox"></td>
						<td width="35%" class="paddleft left table_line1_left">Group Name</td>							
						<td width="30%" class="paddleft left table_line1_left_right">Description</td>
						<td width="30%" class="paddleft left table_line1_left_right">Status</td>
					</tr>
					<tr valign="middle"><td class="bar" colspan="3"></td></tr>		
			<?php
				$bgclass = "";
				if($num_rows>0){	
					for($i=0;$i<$num_rows;$i++){
					
						$ids 				= mysql_result($result, $i, "groupCode");									
						$groupname		 	= mysql_result($result, $i, "groupName");				
						$description	 	= mysql_result($result, $i, "groupCode");
						$systemStatus	 	= mysql_result($result, $i, "systemStatus");		
						 
						 
						$servername         = $_SERVER['SERVER_NAME'];
						$uri 				= substr($_SERVER['REQUEST_URI'], 1);
						$url 				= explode('/',substr($_SERVER['REQUEST_URI'], 1));
						
						 
				 
						$modtype 			=	$type;					 
						
						$site 				= $url[0];			 
						$greybox_url		='<a href="index_no_head.php?mod='.$mod.'&type='.$type.'&task=view&sid='.$ids.'" target="_blank" title="View User" rel="gb_page[1200, 1100]" class="none" title="View this user">View</a>';
					
						
							$link				= "index.php?mod=$mod&type=$type&task=view&sid=$ids";
							$bgclass	 = ($bgclass == 'odd_row') ? 'even_row' : 'odd_row';	 							
							echo "<tr class=\"$bgclass\" id=\"$link\" onClick=\"viewRecord(event,this.id);\">";
							echo '	<td align="center" class="table_line2_left"><input class="ibox" value="'.mysql_result($result, $i, "groupCode").'" name="delAnn[]" onclick="checkAllFields(2);" type="checkbox"></td>
									<td class="table_line2_left page_title paddleft" valign="top" align="left">'.$groupname.'&nbsp;</td>											
									<td class="table_line2_left paddleft caption" valign="top" align="left">'.stripslashes(html_entity_decode($description)).'&nbsp;</td>
									<td class="table_line2_left_right paddleft caption" valign="top" align="left">'.$systemStatus.'&nbsp;</td>';
							echo '</tr>';
					}
				}else{
					echo '<tr valign="middle" width="20px"><td colspan="4" class="table_line2_left table_line2_left_right" align="center"><p class="errmsg">NO RECORDS FOUND</p></td></tr>';
				}
				echo '<tr valign="middle"><td class="bar" colspan="4"></td></tr>		
					  <tr valign="middle" width="18px">
							<td class="table_footer_left" align="center" colspan="2">&nbsp;'.$PAGINATION_INFO.'</td>						
							<td class="table_footer_left_right" align="center" colspan="2"><span class="link">'.$PAGINATION_LINKS.'</span></td>
					  </tr>';		
				?>		
				
				 

			</table>
			
			</form>
		</td>
	</tr>
</table>


<? include_once('view/template1/graybox.php');?>		
<? include_once('view/template1/message.php');?>		



