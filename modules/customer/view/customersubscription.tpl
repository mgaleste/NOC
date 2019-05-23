<div class="idTabs">
  <ul>
    <li><a href="#clientinfo" tabindex="-1">Customer Subscription</a></li>
  </ul>
<div class="items">
<form method="POST" id="clientinfo_Form">
<table width="100%" class="form " id="clientinfo" cellpadding="10" cellspacing="10" border="0">
<tr><td align="center" class="req"><?=$error_messages;?></td></tr>
<tr valign="top" >
		<td style="width:95%; height:100%;" >
			<table width="100%" border="0" cellpadding="2" cellspacing="2">
				<tr>
					<td style="width:10%"><?=$mform->label('customer',"Customer","","req")?></td>
					<td style="width:20%">
                                                <select name="customerId" >
                                                <option value=""> --Select One-- </option>
                                                <?
								$retrieveCustomers = $recon->retrieveCustomQuery("SELECT customerId, firstName, middleName, lastName FROM customer WHERE statusId not in ('-1','15')");
								foreach($retrieveCustomers as $customers){
									$customer = explode("|",$customers);
									echo '<option value="'.$customer[0].'">'.$customer[3].', '.$customer[1].' '.$customer[2].' </option>';
								}
							?>
                                                </select>
                                        </td>

					
				</tr>
                                <tr>
					<td colspan="6"><hr/></td>
				</tr>
				<tr>
					<td style="width:10%"><?=$mform->label('site',"Site","","req")?></td>
					<td style="width:20%">
                                                <select name="siteId" >
                                                <option value=""> --Select One-- </option>
                                                <?
								$retrieveServiceProviders = $recon->retrieveCustomQuery("SELECT siteId, SiteName FROM site WHERE statusId<>'-1' and statusId='2'");
								foreach($retrieveServiceProviders as $serviceProviders){
									$serviceProvider = explode("|",$serviceProviders);
									echo '<option value="'.$serviceProvider[0].'">'.$serviceProvider[1].' </option>';
								}
							?>
                                                </select>
                                        </td>

					
				</tr>
				<tr>
					<td style="width:10%"><?=$mform->label('speed',"Speed","","req")?></td>
					<td style="width:20%">
                                                <select name="speed" >
                                                <option value=""> --Select One-- </option>
                                                <?
								$retrieveServiceProviders = $recon->retrieveCustomQuery("SELECT speed FROM speed order by speedId");
								foreach($retrieveServiceProviders as $serviceProviders){
									$serviceProvider = explode("|",$serviceProviders);
									echo '<option value="'.$serviceProvider[0].'">'.$serviceProvider[0].' </option>';
								}
							?>
                                                </select>
                                        </td>


				</tr>
				
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="right">
			<?php
			if($task!='view'){
				echo $mform->inputBox('create','submit','Save','Save','flat_button','Save','','" ','','23');
				echo '&nbsp;&nbsp;&nbsp;';
				echo $mform->inputBox($task,'button','cancel','Cancel','flat_button','cancel','',' onClick="cancelChanges(\'index.php?mod='.$mod.'&type=customer\');" ','','24');
			}else{
				echo $mform->inputBox($taskView,'button','edit','Edit','flat_button','edit','',' onClick="redirect(\'index.php?mod='.$mod.'&type='.$type.'&task=edit&sid='.$sid.'\');" ','','23');
				echo '&nbsp;&nbsp;&nbsp;';
				echo $mform->inputBox($task,'button','cancel','Cancel','flat_button','cancel','',' onClick="cancelChanges(\'index.php?mod='.$mod.'&type=customer\');" ','','24');
			}
			
                        echo $mform->inputBox('create','hidden','task','create','flat_input','task','60','','','-1');
                        ?>
		</td>
	</tr>
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
					}else{
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