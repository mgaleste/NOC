<? if ($type == 'pass'){?>
		<table width="100%" height="100" align="center" border="0" cellspacing="10" cellpadding="10" class="form">
			<tr>
				<td valign="top">
					<form method="post" ENCTYPE="multipart/form-data" style="display: inline; margin: 0;">
					<table width="100%" border="0" cellspacing="10" cellpadding="0">
						<?php if($errmsg != ""){ ?>							
						<tr><td align="center" colspan="2" class="req"><?=$errmsg;?></td></tr>
						<tr><td height="5">&nbsp;</td></tr>
						<?php } ?>											
						
						
						<tr><td class="caption"><?=$mform->label('current','Enter Current Password','caption','req')?></td><td><input type="password" name="curr_pass" value="" class="inputboxes width300"></td></tr>
						<tr><td class="caption"><?=$mform->label('new','Enter New Password','caption','req')?></td><td><input type="password" name="new_pass" value="" class="inputboxes width300"></td></tr>
						<tr><td class="caption"><?=$mform->label('retype','Retype New Password','caption','req')?></td><td><input type="password" name="renew_pass" value="" class="inputboxes width300"></td></tr>
						<tr><td height="5">&nbsp;</td></tr>
						<tr><td colspan="2" align="left"><input type="submit" class="button2 roundbuttons" name="buttones" value="Update"></td></tr>
					</table>
					</form>
				</td>
			</tr>
		</table>
<?}?>

<? 
if ($type == 'info'){
if ($task == 'view'){?>

<table width="100%" align="center" class="form" cellpadding="10" cellspacing="10">
	<tr>
		<td>
		<form method="POST">
			<table width="100%" cellpadding="5" cellspacing="0">
				<tr><td colspan="2">
					<a href="index.php?mod=profile&type=info&task=edit">Edit Profile</a> 
					<?if($_SESSION['gp_group']=='admin'){?>
					| <a href="index.php?mod=profile&type=pass">Change Password</a>
					<?}?>
				</td></tr>				
				<tr><td height="5">&nbsp;</td></tr>
				<tr><td class="caption"><?=$mform->label('username','Username','caption')?></td><td><?=$username?></td></tr>
				<tr><td class="caption"><?=$mform->label('status','Status','caption')?></td><td><?=$status_name?></td></tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td class="caption"><?=$mform->label('fname','First Name','caption')?></td><td><?=$fname?></td></tr>				
				<tr><td class="caption"><?=$mform->label('mname','Middle Name','caption')?></td><td><?=$mname?></td></tr>				
				<tr><td class="caption"><?=$mform->label('lname','Last Name','caption')?></td><td><?=$lname?></td></tr>				
			</table>	
		</form>
		</td>
	</tr>	
</table>	

<? }elseif ($task == 'edit'){?>

<table width="100%" class="form" align="center" cellpadding="10" cellspacing="10">
	<tr>
		<td>
		<form method="POST"  ENCTYPE="multipart/form-data">
			<table width="100%" cellpadding="2" cellspacing="10">
				<tr><td colspan="2" class="req"><?=$errmsg?></td></tr>	
				<tr><td  class="caption"><?=$mform->label('fname','First Name','caption','req')?></td><td><input type="text" class="inputboxes width300" value="<?=$fname?>" name="fname"/></td></tr>
				<tr><td  class="caption"><?=$mform->label('mname','Middle Name','caption')?></td><td><input type="text" class="inputboxes width300" value="<?=$mname?>" name="mname"/></td></tr>	
				<tr><td  class="caption"><?=$mform->label('lname','Last Name','caption','req')?></td><td><input type="text" class="inputboxes width300" value="<?=$lname?>" name="lname"/></td></tr>	
				<tr><td colspan="2">&nbsp;</td></tr>					
				<tr><td colspan="2"><input type="submit" class="button2 roundbuttons" name="Edit" value="Edit"></td></tr>							
			</table>	
		</form>
		</td>
	</tr>	
</table>	
<?}

}?>
