<table width="100%" cellpadding="0" cellspacing="0" border="0"> 
  <tr>
		<td>
			<table width="100%" class="form" cellpadding="10" cellspacing="10" border="0">
				<tr><td  colspan="2" align="left" class="header"><?=strtoupper(rep_under($type))?></td></tr>
				 				
				<tr>
				<td valign="top">	
					<form method="POST" enctype="multipart/form-data">
					
								<table cellpadding="0" cellspacing="10" border="0" width="1000px">
								<? if($isEnableJob=="active"){?>
									<tr>
										<td width="120px"><?= $mform->label('email','Job Email','caption')?></td>
										<td><?= $mform->inputBox($task,'text','jobemail',stripslashes(html_entity_decode($jobemail)),'input iputshort','jobemail','50');?></td>
									</tr>  
								<?}?>	 
								<? if($isEnableContact=="active"){?>								
									<tr>
										<td width="120px"><?= $mform->label('email','Admin Email','caption')?></td>
										<td><?= $mform->inputBox($task,'text','adminemail',stripslashes(html_entity_decode($adminemail)),'input iputshort','adminemail','50');?></td>
									</tr>  
									 
									<tr>
										<td width="120px"><?= $mform->label('adminname','Admin Name','caption')?></td>
										<td><?= $mform->inputBox($task,'text','adminname',stripslashes(html_entity_decode($adminname)),'input iputshort','adminname','50');?></td>
									</tr>  
								<?}?>	 	
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
 
  