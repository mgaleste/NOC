 
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
						<th width="25%" class="left paddleft table_line1_left">Username</th>
						<th width="20%" class="left paddleft table_line1_left">Last Name</th>
						<th width="20%" class="left paddleft table_line1_left">First Name</th>
						<th width="30%" class="left paddleft table_line1_left_right">User Group</th>						
					</tr>
					<tr valign="middle"><th class="bar" colspan="5"></th></tr>		
				</thead>
				
				<tfoot>
					<tr valign="middle"><td class="bar" colspan="5"></td></tr>		
					<tr valign="middle">
						<td width="5%" 	align="center" class="table_line1_left">&nbsp;</td>
						<td width="25%" class="left paddleft table_line1_left">Username</td>
						<td width="20%" class="left paddleft table_line1_left">Last Name</td>
						<td width="20" 	class="left paddleft table_line1_left">First Name</td>
						<td width="30%" class="left paddleft table_line1_left_right">User Group</td>						
					</tr>
					<tr valign="middle">
						<td class="table_footer_left" align="center" colspan="2"><?=$PAGINATION_INFO?></td>
						<td class="table_footer_left_right"   colspan="3"  align="center"><span class="link"><?=$PAGINATION_LINKS?></span></td>
					</tr>					
				</tfoot>
				
				<tbody>		
			<?php
				$bgclass = "";
				if($num_rows>0){	
					for($i=0;$i<$num_rows;$i++){
					
						$ids 				= mysql_result($result, $i, "username");									
						$username		 	= mysql_result($result, $i, "username");				
						$lastname		 	= mysql_result($result, $i, "lastName");	
						$firstname		 	= mysql_result($result, $i, "firstName");				
						$status			 	= mysql_result($result, $i, "systemStatus");
						$groupCode			 	= mysql_result($result, $i, "groupCode");								
						 
						$servername         = $_SERVER['SERVER_NAME'];
						$uri 				= substr($_SERVER['REQUEST_URI'], 1);
						$url 				= explode('/',substr($_SERVER['REQUEST_URI'], 1));
						$modtype = $type;
						$site 				= $url[0];
			 
						$greybox_url		='<a href="index_no_head.php?mod=users&type=users&task=view&sid='.$ids.'" target="_blank" title="View User" rel="gb_page[1200, 1100]" class="none" title="View this user">View</a>';
					
							$link				= "index.php?mod=$mod&type=$type&task=view&sid=$ids";
							$bgclass	 = ($bgclass == 'odd_row') ? 'even_row' : 'odd_row'; 							
							echo "<tr class=\"$bgclass\" id=\"$link\" onClick=\"viewRecord(event,this.id);\">";
							echo '	<td align="center" class="table_line2_left"><input class="ibox" value="'.mysql_result($result, $i, "username").'" name="delAnn[]" onclick="checkAllFields(2);" type="checkbox"></td>
									<td class="table_line2_left paddleft page_title" valign="top" align="left">'.$username.'&nbsp;</td>								
									<td class="table_line2_left paddleft caption" valign="top" align="left">'.stripslashes(html_entity_decode($lastname)).'&nbsp;</td>															
									<td align="left" valign="top"  class="table_line2_left paddleft category">'.stripslashes(html_entity_decode($firstname)).'&nbsp;</td>
									<td align="left" valign="top"  class="table_line2_left_right paddleft category">'.stripslashes(html_entity_decode($groupCode)).'&nbsp;</td>';
							echo '</tr>';
					}
				}else{
					echo '<tr valign="middle" width="20px"><td colspan="5" class="table_line2_left table_line2_left_right" align="center"><p class="errmsg">NO RECORDS FOUND</p></td></tr>';
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


