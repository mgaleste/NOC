<table width="100%"   cellpadding="0" cellspacing="0" border="0">
  <tr>
		<td>
			<table width="100%" class="form" cellpadding="10" cellspacing="10" border="0">
				<tr><td  colspan="2" align="left" class="header"><a href="index.php?mod=<?=$mod?>&type=<?=$type?>"><?=strtoupper(rep_under($type))?></a></td></tr>
				 				
				<tr>
					<td valign="top">	
					<form method="POST" enctype="multipart/form-data">
								<table cellpadding="0" cellspacing="10" border="0" width="1000px">
									 
									<tr>
										<td width="120px"><?= $mform->label('phone','Phone','caption')?></td>
										<!--<td><?= $mform->inputBox($task,'text','phone',stripslashes($phone),'input iputshort','phone','50');?></td>-->
										<td><textarea name="phone"><?=stripslashes($phone)?></textarea></td>
									</tr>
									
									<tr>
										<td width="120px"><?= $mform->label('fax','Fax','caption')?></td>
										<td><?= $mform->inputBox($task,'text','fax',stripslashes(html_entity_decode($fax)),'input iputshort','fax','50');?></td>
									</tr>
									<tr>
										<td width="120px"><?= $mform->label('email','Email','caption')?></td>
										<td><?= $mform->inputBox($task,'text','email',stripslashes(html_entity_decode($email)),'input iput','email','50');?></td>
									</tr>
									<tr>
										<td width="120px"><?= $mform->label('address','Address','caption')?></td>										 
										<td><?= $mform->textarea($task,'address',stripslashes(html_entity_decode($address)),'20','20','tinymce txtarea','innerelm1');?></td>
									</tr> 
									<tr><td colspan="2">&nbsp;</td></tr>	
									<tr>
										<td align="left" colspan="2"><input type="submit" name="save" value="Save" class="button2 roundbuttons"></td> 
									</tr>
								</table>
								<div class="message"><p><? if(!empty($errmsg)){ foreach($errmsg as $errValue){ echo $errValue; } }?></p></div>
						</form> 
					</td>
				</tr>	 
			</table>
		</td> 
</tr>


</table>
 
 
<script type="text/javascript" > 
		function changeImageCaption(){	 		 					 
					var test5	= document.getElementById('images5').value;
					document.getElementById('imagecaption5').value = test5;
		} 
		
</script>	<?include_once('view/template1/tinymceconfig.php');?>