<div class="idTabs">
  <ul>
    <li><a href="#clientinfo" tabindex="-1">Ticket Information</a></li>
  </ul>
<div class="items">
<form method="POST" id="clientinfo_Form">
<table width="100%" class="form " id="clientinfo" cellpadding="10" cellspacing="10" border="0">
<tr><td align="center" class="req"><?=$error_messages;?></td></tr>
<tr valign="top" >
		<td style="width:95%; height:100%;" >
			<table width="100%" border="0" cellpadding="2" cellspacing="2">
				<tr>
                                    <td style="width:10%"><?=$mform->label('ticketId',"Ticket ID","","req")?></td>
                                    <td style="width:20%" >
                                        <?
                                            $isReadOnlyTicket=(empty($ticketId) && $task=='create')? 'disabled' : '';
                                            $isReadOnlyTicket=($task=='changestatus')? 'readonly' : $isReadOnlyTicket;
                                            $ticketId=(empty($ticketId) && $task=='create')? 'AUTO-GENERATED' : $ticketId;
                                            
                                        ?>
                                        <?=$mform->inputBox($task,'text','ticketId',$ticketId,'flat_input','ticketId','50',$isReadOnlyTicket,'','1')?></td>
                                        <?
                                        if($task=='changestatus'){
                                        ?>
                                        <td style="width:10%"><?=$mform->label('status',"Status","","req")?></td>
					<td style="width:20%">
                                                <select name="statusId" >
                                                <option value=""> --Select One-- </option>
                                                <?
								$retrieveServiceProviders = $recon->retrieveCustomQuery("SELECT statusId, name FROM status WHERE type='ticket' ");//and statusId='2'
								foreach($retrieveServiceProviders as $serviceProviders){
									$serviceProvider = explode("|",$serviceProviders);
                                                                        $selected = ($serviceProvider[0]==$statusId)? 'selected' : '';
									echo '<option value="'.$serviceProvider[0].'" '.$selected.'>'.$serviceProvider[1].' </option>';
								}
							?>
                                                </select>
                                        </td>
                                        <? } ?>

                                </tr>
                                <tr>
					<td style="width:10%"><?=$mform->label('problem',"Problem","","req")?></td>
					<td style="width:20%" colspan="5"><?=$mform->inputBox($task,'text','problem',$problem,'flat_input','problem','50','',$isReadOnly,'1')?></td>
                                </tr>
                                <tr valign="top">
					<td ><?=$mform->label('description',"Description","","req")?></td>
					<td colspan="5"><?=$mform->textarea($task,'description',$description,'4','2','width100 '.$ro_class,'description','','2',$isReadOnly)?></td>
				</tr>
                                <tr>
					<td colspan="6"><hr/></td>
				</tr>
                                <?
                                if($task=='changestatus'){
                                ?>
                                <tr valign="top">
					<td ><?=$mform->label('resolution',"Resolution","","req")?></td>
					<td colspan="5"><?=$mform->textarea($task,'resolution',$resolution,'4','2','width100 '.$ro_class,'resolution','','2','')?></td>
				</tr>
                                <tr>
					<td colspan="6"><hr/></td>
				</tr>
                                <?
                                    }
                                ?>
                                <tr>
					<td style="width:10%"><?=$mform->label('site',"Site","","req")?></td>
					<td style="width:20%">
                                                <select name="siteId" >
                                                <option value=""> --Select One-- </option>
                                                <?              $query = ($task !='changestatus')? "SELECT siteId, SiteName FROM site WHERE statusId<>'-1' " : "SELECT siteId, SiteName FROM site WHERE siteId='$siteId' ";
								$retrieveServiceProviders = $recon->retrieveCustomQuery($query);//and statusId='2'
								foreach($retrieveServiceProviders as $serviceProviders){
									$serviceProvider = explode("|",$serviceProviders);
                                                                        $select = ($serviceProvider[0]==$siteId)? 'selected' : '';
									echo '<option value="'.$serviceProvider[0].'" '.$select.'>'.$serviceProvider[1].' </option>';
								}
							?>
                                                </select>
                                        </td>
					
					<td style="width:10%"><?=$mform->label('customer',"Customer","","")?></td>
					<td style="width:20%">
                                                <select name="customerId" >
                                                <option value=""> --Select One-- </option>
                                                <?              $query = ($task !='changestatus')? "SELECT customerId, firstName, middleName, lastName FROM customer WHERE statusId<>'-1' " : "SELECT customerId, firstName, middleName, lastName FROM customer WHERE username='{$_SESSION['gp_username']}' ";
								$retrieveCustomers = $recon->retrieveCustomQuery("SELECT customerId, firstName, middleName, lastName FROM customer WHERE statusId<>'-1'");
								foreach($retrieveCustomers as $customers){
									$customer = explode("|",$customers);
                                                                        $select = ($customer[0]==$customerId)? 'selected' : '';
                                                                        $select = ($groupCode=='customer')? 'selected' : $select;
									echo '<option value="'.$customer[0].'" '.$select.'>'.$customer[3].', '.$customer[1].' '.$customer[2].' </option>';
								}
							?>
                                                </select>
                                        </td>

                                        <td style="width:10%">&nbsp;</td>
					<td style="width:20%">&nbsp;</td>
				</tr>
                                
				
                                
				
				
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="right">
			<?php
			if($task!='view'){
				echo $mform->inputBox($task,'submit','Save','Save','flat_button','Save','','" ','','23');
				echo '&nbsp;&nbsp;&nbsp;';
				echo $mform->inputBox($task,'button','cancel','Cancel','flat_button','cancel','',' onClick="cancelChanges(\'index.php?mod='.$mod.'&type='.$type.'\');" ','','24');
			}else{
				echo $mform->inputBox($taskView,'button','edit','Edit','flat_button','edit','',' onClick="redirect(\'index.php?mod='.$mod.'&type='.$type.'&task=changestatus&sid='.$sid.'\');" ','','23');
				echo '&nbsp;&nbsp;&nbsp;';
				echo $mform->inputBox($task,'button','cancel','Cancel','flat_button','cancel','',' onClick="cancelChanges(\'index.php?mod='.$mod.'&type='.$type.'\');" ','','24');
			}
			
                        echo $mform->inputBox($task,'hidden','task',$_GET['task'],'flat_input','task','60','','','-1');
                        ?>
		</td>
	</tr>
<?
$result = mysql_query("select startDate, resolution, s.name spStatus from ticketstatus m join status s using (statusid) where ticketId='$sid' order by startDate Desc LIMIT 0, 5");
$num_rows = @mysql_num_rows($result);
if($num_rows>0){
?>
        <tr>
		<td colspan="2" class="right">
                    <table width="100%" border="0" cellpadding="2" cellspacing="2">
                    <tr>
                        <th width="15%" class="table_line1_left">Date</th>
                        <th width="15%" class="table_line1_left">Resolution</th>
                        <th width="20%" class="table_line1_left_right">Status</th>
                    </tr>
<?
for($i=0;$i<$num_rows;$i++){
                   
                    $resolution		 	= mysql_result($result, $i, "resolution");

                    $startDate		 	= mysql_result($result, $i, "startDate");

                    $customerStatus		 	= mysql_result($result, $i, "spStatus");

                    $statusColor                    = $recon->GetValue('color','status','name="'.$customerStatus.'" and type="ticket"');

                    $bgclass	 = ($bgclass == 'odd_row') ? 'even_row' : 'odd_row';
                    echo "<tr class=\"$bgclass list_row\" >";
                    echo '
                        <td class="table_line2_left">'.stripslashes(html_entity_decode($startDate)).'&nbsp;</td>
                        <td class="table_line2_left">'.stripslashes(html_entity_decode($resolution)).'&nbsp;</td>
                        <td class="table_line2_left_right" align="center"><b style="color:#'.$statusColor.'">'.stripslashes(html_entity_decode($customerStatus)).'</b>&nbsp;</td>';
                    echo '</tr>';
}
?>

                    </table>
                </td>
	</tr>
<?
}
?>
</table>
</form>
</div>
<script type="text/javascript">
/**CANCEL CHANGES**/
	function cancelChanges(url)
	{
		var task= document.getElementById('task').value;
		if(task != 'view'){
			
			var prompt_text = "Are you sure you want to cancel all changes?";
			$.prompt(prompt_text,
			{buttons:{Yes:1, No:0},
			submit: function(e,v,m,f){
				if(v==1){
					$.prompt.close();
					if(task == 'create'){
						location.href = url;
					}else if(task == 'edit'){
						location.href = url;
					}else if(task == 'changestatus'){
						location.href = url;
					}
				}
			}
			});
		}else{
			location.href = url;
		}
		localStorage.clear();
	}

	/**REDIRECT**/
	function redirect(url)
	{
		location.href = url;
	}
</script>