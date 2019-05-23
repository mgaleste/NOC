
<div class="idTabs">
  <ul>
    <li><a href="#clientinfo" tabindex="-1">Customer Subscription Payment</a></li>
  </ul>
<div class="items">
<form method="POST" id="clientinfo_Form">
<table width="100%" class="form " id="clientinfo" cellpadding="10" cellspacing="10" border="0">
<tr><td align="center" class="req"><?=$error_messages;?></td></tr>
<tr valign="top" >

		<td style="width:95%; height:100%;" >
			<table width="100%" border="0" cellpadding="2" cellspacing="2">
				<tr>
					<td style="width:10%" colspan="2"><?=$mform->label('customerSubscription',"Customer Subscription","","req")?></td>
					<td style="width:20%" colspan="4">
                                            <select name="customerSubscriptionId" >
                                                <option value=""> --Select One-- </option>
                                                <?
								$retrieveCustomers = $recon->retrieveCustomQuery("SELECT customerSubscriptionId, c.firstName, c.middleName, c.lastName, sp.siteName FROM customersubscription m
                                                                join customer c on m    .customerId=c.customerId
                                                                join site sp on m.siteId=sp.siteId
                                                                 WHERE m.statusId <>'-1' and m.statusId='15'");
								foreach($retrieveCustomers as $customers){
									$customer = explode("|",$customers);
                                                                        $selected = ($customer[0]==$customerSubscriptionId)? 'selected' : '' ;
									echo '<option value="'.$customer[0].'" '.$selected .'>'.$customer[3].', '.$customer[1].' '.$customer[2].' | '.$customer[4].'</option>';
								}
							?>
                                                </select>
                                        </td>

					</tr>
<tr>
					<td style="width:10%"><?=$mform->label('amount',"Amount","","req")?></td>
					<td style="width:20%"><?=$mform->inputBox($task,'text','amount',$amount,'flat_input '.$ro_class,'amount','20','onKeyPress="return isNumberKey(event,46)"',$isReadOnly,'4')?></td>

					<td style="width:10%"><?=$mform->label('dueDate',"Due Date","","req")?></td>
					<td style="width:20%">
                                            <?=$mform->inputBox($taskView,'text','dueDate',$dueDate,'flat_input '.$ro_class,'dueDate','5',$isReadOnly,'','10')?> <?= $mform->button($task,'dueDateTrigger','calbutton');?>
                                        </td>

                                        
				</tr>
                                <tr>
					<td colspan="6"><hr/></td>
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
				echo $mform->inputBox($taskView,'button','paid','Paid','flat_button','paid','',' onClick="redirect(\'index.php?mod='.$mod.'&type='.$type.'&task=paid&sid='.$sid.'\');" ','','23');
				echo '&nbsp;&nbsp;&nbsp;';
                                echo $mform->inputBox($taskView,'button','cancel','Cancel','flat_button','cancel','',' onClick="redirect(\'index.php?mod='.$mod.'&type='.$type.'&task=cancel&sid='.$sid.'\');" ','','23');
				echo '&nbsp;&nbsp;&nbsp;';
                                echo $mform->inputBox($taskView,'button','overdue','Overdue','flat_button','overdue','',' onClick="redirect(\'index.php?mod='.$mod.'&type='.$type.'&task=overdue&sid='.$sid.'\');" ','','23');
				echo '&nbsp;&nbsp;&nbsp;';
				echo $mform->inputBox($task,'button','cancel','Cancel','flat_button','cancel','',' onClick="cancelChanges(\'index.php?mod='.$mod.'&type='.$type.'\');" ','','24');
			}
			
                        echo $mform->inputBox($task,'hidden','task',$_GET['task'],'flat_input','task','60','','','-1');
                        ?>
		</td>
	</tr>
</table>
</form>
</div>

<script type="text/javascript">
/**VALIDATION FOR NUMERIC FIELDS**/
	function isNumberKey(evt,exemptChar) {
		if(evt.which != 0){
			var charCode = (evt.which) ? evt.which : event.keyCode 
			if(charCode == exemptChar) return true; 
			if (charCode > 31 && (charCode < 48 || charCode > 57)) 
			return false; 
			return true;
		}
	}
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
<? if($task!='view'){?>
<script type="text/javascript">
	/**CALENDAR PLUGIN FUNCTION**/
	Calendar.setup
	(
	{
		inputField  : "dueDate",  // ID of the input field
		ifFormat    : "%Y-%m-%d",        // the date format
		button      : "dueDateTrigger"           // ID of the button
	}
	);
</script>

<?}?>