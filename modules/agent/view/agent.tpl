
<table width="98%" class="form" align="center" cellpadding="10" cellspacing="10">
	<tr>
		<td>
		<form method="POST"  ENCTYPE="multipart/form-data">
			<table width="100%" cellpadding="2" cellspacing="0">
 			
				<tr><td colspan="2">&nbsp;</td></tr>			
				<tr>
						<td valign="top"><?= $mform->label('groupaccess','Group Access ','caption','req')?></td>
				<?if((strtolower($task)=='create') || (strtolower($task)=='edit') ){ ?>				
						<td><select name="groupCode" class="flat_input '.$ro_class.'">
							<option value="0">[Select One]</option>
							<?php
								$con->qselectDb("SELECT * FROM groups WHERE groupName!='' ORDER BY groupName");
									while($con->fetchRs()){
										if($groupCode == $con->rs['groupCode']){
											$selected = "selected";
										}else{
											$selected = "";
										}
							?>
										<option value="<?=$con->rs['groupCode']?>" <?=$selected?>><?=$con->rs['groupName']?></option>
							<?php
									}
							?>		
						</select>	
						</td>
				<?}else{?>					
						<td><?=$mform->inputBox('edit','text','userlevel',$groupCode,'flat_input '.$ro_class.' ','userlevel','200','readOnly');?></td>
				<?}?>					
				</tr>
				<tr>
					<td valign="top"><?= $mform->label('firstname','First Name ','caption','req')?></td>
					<td><?= $mform->inputBox($taskView,'text','firstName',$firstName,'flat_input '.$ro_class.'','firstname','200',$isReadOnly	);?></td>
				</tr>
				
				
				<tr>
					<td valign="top"><?= $mform->label('mname','Middle Name ','caption')?></td>
					<td><?= $mform->inputBox($taskView,'text','middleName',$middleName,'flat_input '.$ro_class.'','mname','200',$isReadOnly	);?></td>
				</tr>
				
				<tr>
					<td valign="top"><?= $mform->label('lastname','Last Name ','caption','req')?></td>
					<td><?= $mform->inputBox($taskView,'text','lastName',$lastName,'flat_input '.$ro_class.'','lastname','200',$isReadOnly	);?></td>
				</tr>
				
				<tr>
					<td valign="top"><?= $mform->label('username','Username ','caption','req')?></td>
					<?if($taskView=='create'){?>
					<td><input type="text" class="flat_input '.$ro_class.'" value="<?=$username?>" name="username"/></td>
					<?}else if($taskView=='edit'){?>	
					<td><input type="text" disabled class="flat_input <?=$ro_class?>" value="<?=$username?>" name="username"/></td>
					<?}else{?>
					<td><?=$username?></td>
					<?}?>
				</tr>
					
				 
				<tr><td colspan="2">&nbsp;</td></tr>	
				
			<?if((strtolower($task)=='create') || (strtolower($task)=='edit') ){ ?>				
				<tr><td colspan="2"><input type="submit" class="flat_button" name="Save" value="Save"></td></tr>				
			<?}else{?>					
				<tr><td colspan="2"><?=$mform->inputBox($task,'button','cancel','Cancel','flat_button','cancel','','','','24');?></td></tr>
			<?}?>	
			</table>
			<div class="message"><p><? if(!empty($errmsg)){ foreach($errmsg as $errValue){ echo $errValue; } }?></p></div>			
		</form>
		</td>
	</tr>	
</table>	
