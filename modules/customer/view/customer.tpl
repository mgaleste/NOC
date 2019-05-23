<div class="idTabs">
  <ul>
    <li><a href="#clientinfo" tabindex="-1">Customer Information</a></li>
  </ul>
<div class="items">
<form method="POST" id="clientinfo_Form">
<table width="100%" class="form " id="clientinfo" cellpadding="10" cellspacing="10" border="0">
<tr><td align="center" class="req"><?=$error_messages;?></td></tr>
<tr valign="top" >
		<td style="width:95%; height:100%;" >
			<table width="100%" border="0" cellpadding="2" cellspacing="2">
				<tr>
					<td style="width:10%"><?=$mform->label('firstName',"First Name","","req")?></td>
					<td style="width:20%"><?=$mform->inputBox($task,'text','firstName',$firstName,'flat_input '.$ro_class,'firstName','20','',$isReadOnly,'1')?></td>

					<td style="width:10%"><?=$mform->label('middleName',"Middle name")?></td>
					<td style="width:20%"><?=$mform->inputBox($task,'text','middleName',$middleName,'flat_input '.$ro_class,'middleName','20',$isReadOnly,'','2')?></td>

					<td style="width:10%"><?=$mform->label('lastName',"Last Name","","req")?></td>
					<td style="width:20%"><?=$mform->inputBox($task,'text','lastName',$lastName,'flat_input '.$ro_class,'lastName','20',$isReadOnly,'','3')?></td>
				</tr>
<tr>
					<td style="width:10%"><?=$mform->label('username',"User Name","","req")?></td>
					<td style="width:20%"><?=$mform->inputBox($task,'text','username',$username,'flat_input '.$ro_class,'username','20','',$isReadOnly,'4')?></td>

					<td style="width:10%">&nbsp;</td>
					<td style="width:20%">&nbsp;</td>

                                        <td style="width:10%">&nbsp;</td>
					<td style="width:20%">&nbsp;</td>
				</tr>
                                <tr>
					<td colspan="6"><hr/></td>
				</tr>
				<tr>
					<td style="width:10%"><?=$mform->label('email',"eMail","","req")?></td>
					<td style="width:20%"><?=$mform->inputBox($task,'text','email',$email,'flat_input '.$ro_class,'email','20',$isReadOnly,'','5')?></td>

					<td style="width:10%"><?=$mform->label('contactNumber',"Contact Number","","req")?></td>
					<td style="width:20%"><?=$mform->inputBox($task,'text','contactNumber',$contactNumber,'flat_input '.$ro_class,'contactNumber','20','onKeyPress="return isNumberKey(event,46)"',$isReadOnly,'6')?></td>

					<td style="width:10%">&nbsp;</td>
					<td style="width:20%">&nbsp;</td>
				</tr>
                                <tr valign="top">
					<td ><?=$mform->label('address',"Address","","req")?></td>
					<td colspan="5"><?=$mform->textarea($task,'address',$address,'4','2','width100 '.$ro_class,'address','','7',$isReadOnly)?></td>
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
				echo $mform->inputBox($taskView,'button','edit','Edit','flat_button','edit','',' onClick="redirect(\'index.php?mod='.$mod.'&type='.$type.'&task=edit&sid='.$sid.'\');" ','','23');
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