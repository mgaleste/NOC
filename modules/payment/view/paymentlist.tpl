<table width="100%" class="form  paddright" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td align="" class="header"></td>
		<td align="right">	
			<form method="POST">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="4" align="right"><?= $mform->inputBox($task,'text','psearch_entry',$psearch,'search input','psearch_entry','15');?></td>
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
									<td class="verdana10"><input style="border:none" type="image" src="<?=MODIMAGES;?>delete.gif" name="submit_delete" width="16" height="16"><button name="submit_delete" class="delbut">Cancel</button>[<input name="hidden_selected" class="verdana10 input_hidden" type="text" id="removeChecked" value="0">]<button name="submit_delete"  class="delbut"><?=rep_under(ucfirst($type))?>(s)</button></td>
									<td  align="right" class="verdana10"></td>
								</tr>
							</table>
						</th>
					</tr> 
					<tr><th colspan="5">&nbsp;</th></tr>
					<tr valign="middle">
						<th width="5%" align="center" class="table_line1_left"><input onclick="checkAllFields(1);" id="checkAll" type="checkbox"></th>
						<th width="20%" class="table_line1_left">Customer Name</th>
						<th width="20%" class="table_line1_left">SiteName</th>
						<th width="15%" class="table_line1_left">Due Date</th>
						<th width="15%" class="table_line1_left_right">Status</th>
					</tr>
					<tr valign="middle"><th class="bar" colspan="5"></th></tr>
				</thead>
				
				<tfoot>
					<tr valign="middle"><td class="bar" colspan="5"></td></tr>
					<tr valign="middle">
						<td width="5%" class="table_line1_left">&nbsp;</td>
						<td width="20%" class="table_line1_left"></td>
						<td width="20%" class="table_line1_left"></td>
						<td width="15%" class="table_line1_left"></td>
						<td width="20%" class="table_line1_left_right"></td>
					</tr>
					<tr valign="middle">
						<td class="table_footer_left" align="center" colspan="2"><?=$PAGINATION_INFO?></td>
						<td class="table_footer_left"   colspan="2"  align="center"><span class="link"><?=$PAGINATION_LINKS?></span></td>
                                                <td align="right" class="verdana10 table_footer_left_right"> <?= $userProd->showItems();?></td>
					</tr>
				</tfoot>
				
				<tbody>		
			<?php
				$bgclass = "";
				if($num_rows>0){
					$paneContent = "";
					for($i=0;$i<$num_rows;$i++){
					
						$ids 				= mysql_result($result, $i, "subscriptionPaymentId");
						$serviceproviderName		 	= mysql_result($result, $i, "siteName");
						$lastname		 	= mysql_result($result, $i, "lastName");
						$firstname		 	= mysql_result($result, $i, "firstName");
						$middlename		 	= mysql_result($result, $i, "middleName");
                                                $dateDue		 	= mysql_result($result, $i, "dueDate");
                                               
                                                $customerStatus		 	= mysql_result($result, $i, "spStatus");

                                                $statusColor                    = $recon->GetValue('color','status','name="'.$customerStatus.'" and type="payment"');
						$applicantName		= stripslashes(html_entity_decode($lastname)).", ".stripslashes(html_entity_decode($firstname))." ".stripslashes(html_entity_decode($middlename));
						
						$servername         = $_SERVER['SERVER_NAME'];
						$uri 				= substr($_SERVER['REQUEST_URI'], 1);
						$url 				= explode('/',substr($_SERVER['REQUEST_URI'], 1));
						$modtype = $type;
						$site 				= $url[0];
						
						
						$link				= "index.php?mod=$mod&type=$type&task=view&sid=$ids";
                                                $view_link = ($customerStatus<>'Paid' && $customerStatus<>'Cancelled')? 'onClick="viewRecord(event,this.id);"' : '';
                                                $cbox_link = ($customerStatus<>'Paid' && $customerStatus<>'Cancelled')? 'onclick="checkAllFields(2);"' : '';
                                                $cbox_name = ($customerStatus<>'Paid' && $customerStatus<>'Cancelled')?  'name="delAnn[]"' : 'disabled="disablded"';
						
							
							$bgclass	 = ($bgclass == 'odd_row') ? 'even_row' : 'odd_row';						
							echo "<tr class=\"$bgclass list_row\" >";
							echo '	<td align="center" class="table_line2_"><input class="ibox" value="'.$ids.'" '.$cbox_name.' '.$cbox_link.' type="checkbox"></td>
									<td class="table_line2_left" id="'.$link.'" '.$view_link.'>'.$applicantName.'&nbsp;</td>
									<td class="table_line2_left">'.stripslashes(html_entity_decode($serviceproviderName)).'&nbsp;</td>
									<td class="table_line2_left">'.stripslashes(html_entity_decode($dateDue)).'&nbsp;</td>
                                                                        <td align="center" class="table_line2_left_right"><b style="color:#'.$statusColor.'">'.stripslashes(html_entity_decode($customerStatus)).'</b>&nbsp;</td>';
									
							echo '</tr>';
					}
				}else{
					echo '<tr valign="middle" width="20px"><td colspan="6" class="table_line2_ table_line2__right" align="center"><p class="errmsg">NO RECORDS FOUND</p></td></tr>';
				}
				?>		
				</tbody>		
			</table>
			<div class="message"><p><?= (!empty($_GET['errmsg'])) ? $_GET['errmsg'] : ""; ?></p></div>
			</form>
		</td>
	</tr>
</table>
<script type="text/javascript" src="{plug}archiving/checkbox_selectall.js"></script>
<script type="text/javascript" src="{plug}archiving/scripts.js"></script>

<? include_once('view/template1/graybox.php');?>		
<? include_once('view/template1/message.php');?>	


