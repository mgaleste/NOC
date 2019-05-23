 
<table width="98%" class="form" cellpadding="10" cellspacing="10" border="0">
	<tr>
		<td align="left" class="header"><?=strtoupper(rep_under($type))?></td>
		<td align="right">	
			<form method="POST">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="4" align="right"><?= $mform->inputBox($task,'text','psearch_entry',"",'search','psearch_entry','15');?></td>
					<td>&nbsp;<?= $mform->inputBox($task,'submit','doSearch',"Search",'button','doSearch','5');?></td>
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
						<td colspan="5">
							<table cellpadding="0" cellspacing="0" border="0" width="100%">
								<tr valign="middle">
									<td class="verdana10">
										<input style="border:none" type="image" src="<?=MODIMAGES;?>delete.gif" name="submit_delete" width="16" height="16"><button name="submit_delete" class="delbut">Delete</button>[<input name="hidden_selected" class="verdana10 input_hidden" type="text" id="removeChecked" value="0">]<button name="submit_delete"  class="delbut"><?=rep_under(ucfirst($type))?>(s)</button>							
									</td>
									<td  align="right" class="verdana10">
										Item Count:  <select onChange="location.href='?<?=makeURL($_SERVER['QUERY_STRING'])?>&items='+this.value">
													<option value="10" <?=($items==10)?"selected":""?>>10</option>
													<option value="25" <?=($items==25)?"selected":""?>>25</option>
													<option value="50" <?=($items==50)?"selected":""?>>50</option>
													</select>							
									</td>
								</tr>
							</table>
						</td>
					</tr> 
					<tr><td colspan="5">&nbsp;</td></tr>
					<tr valign="middle">
						<td width="5%" align="center" class="table_line1_left"><input onclick="checkAllFields(1);" id="checkAll" type="checkbox"></td> 				
						<td width="20%" class="table_line1_left">FIRST NAME</td>							
						<td width="20%" class="table_line1_left">LAST NAME</td>																		 
						<td width="35%" class="table_line1_left">EMAIL</td>																		 
						<td width="20%" class="table_line1_left_right">JOB TITLE</td>																		 
					</tr>
					<tr valign="middle"><td class="bar" colspan="5"></td></tr>		
			<?php
				$bgcolor = "";
				if($num_rows>0){	
					for($i=0;$i<$num_rows;$i++){
					
						$ids 				= mysql_result($result, $i, "id");									 
						$firstname	 		= mysql_result($result, $i, "firstname");				
						$lastname		 	= mysql_result($result, $i, "lastname");				
						$email			 	= mysql_result($result, $i, "email");										
						$jobtitle		 	= mysql_result($result, $i, "jobtitle");										
						
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
						$inner_pane			= "<div class=\"none\" style=\"padding-top:5px; display:none\" id=\"{$ids}\"><font face=\"verdana\" size=\"1\">&nbsp;&nbsp; <a href=\"index.php?mod=$mod&type=$type&task=delete&sid=$ids\" onclick=\"return confirm('Are you sure you want to delete?');\" class=\"none\" title=\"Delete this page\">Delete </a>| <a href=\"index.php?mod=$mod&type=$type&task=view&sid=$ids\"  class=\"none\" title=\"View Resume\">View Resume </a> </font></div>";						
						
							
							$bgcolor = ($bgcolor != "#FFFFFF")? "#FFFFFF" : "#EFEFEF"; 							
							echo "<tr bgcolor=\"$bgcolor\"  height=\"55px\" >";								 												
							echo '	<td align="center" class="table_line2_left"><input class="ibox" value="'.mysql_result($result, $i, "id").'" name="delAnn[]" onclick="checkAllFields(2);" type="checkbox"></td>
									<td class="table_line2_left page_title" valign="top" align="left" onmouseover="show_div(\''.$ids.'\')" onmouseout="hide_div(\''.$ids.'\')"><a  style="text-decoration:none" href="index.php?mod='.$mod.'&type='.$type.'&task=edit&sid='.$ids.'">'.$firstname.'</a>'.$inner_pane.'</td>			
									<td class="table_line2_left category paddleft" valign="top" align="left">'.$lastname.'</td>
									<td class="table_line2_left category paddleft" valign="top" align="left">'.$email.'</td>
									<td class="table_line2_left_right category paddleft" valign="top" align="left">'.$jobtitle.'&nbsp;</td>';
							echo '</tr>';
					}
				}else{
					echo '<tr valign="middle" width="20px"><td colspan="5" class="table_line2_left table_line2_left_right" align="center"><p class="errmsg">NO RECORDS FOUND</p></td></tr>';
				}
				echo '<tr valign="middle"><td class="bar" colspan="5"></td></tr>		
					<tr valign="middle" width="18px">
						<td class="table_footer_left" align="center"  colspan="2">&nbsp;'.$PAGINATION_INFO.'</td>
						<td class="table_footer_left" align="center"  colspan="2"><span class="link">'.$PAGINATION_LINKS.'</span></td>
						<td class="table_footer_left_right"    align="center">Total Pages: '.$PAGINATION_TOTALRECS.'</td></tr>';		
				?>		
				
				 

			</table>
			</form>
		</td>
	</tr>
</table>
