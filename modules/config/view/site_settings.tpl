<table width="100%" cellpadding="0" cellspacing="0" border="0"> 
  <tr>
		<td>
			<table width="100%" class="form" cellpadding="10" cellspacing="10" border="0">
				<tr><td  colspan="2" align="left" class="header"><a href="index.php?mod=<?=$mod?>&type=<?=$type?>"><?=strtoupper(rep_under($type))?></a></td></tr>
				 				
				<tr>
					<td valign="top">	
					<form method="POST" enctype="multipart/form-data">						 
						<fieldset>
							<legend>Main Site</legend>
								<table cellpadding="0" cellspacing="10" border="0" width="1000px">
						 			<tr><td><br/></td></tr>
									<tr>
										<td width="120px"><?= $mform->label('charset','Charset','caption')?></td>
										<td><?= $mform->inputBox($task,'text','main_charset',stripslashes(html_entity_decode($main_charset)),'input iputshort','main_charset','50');?></td>
									</tr>								 
									<tr>
										<td width="120px"><?= $mform->label('title','Title','caption')?></td>
										<td><?= $mform->inputBox($task,'text','main_title',stripslashes(html_entity_decode($main_title)),'input iputshort','main_title','50');?></td>
									</tr>								
									<tr>
										<td width="120px"><?= $mform->label('author','Author','caption')?></td>
										<td><?= $mform->inputBox($task,'text','main_author',stripslashes(html_entity_decode($main_author)),'input iputshort','main_author','50');?></td>
									</tr> 									
									<tr>
										<td width="120px"><?= $mform->label('description','Description','caption')?></td>
										<td><?= $mform->inputBox($task,'text','main_description',stripslashes(html_entity_decode($main_description)),'input iput','main_description','200');?></td>
									</tr> 									
									<tr>
										<td width="120px"><?= $mform->label('keywords','Keywords','caption')?></td>
										<td><?= $mform->inputBox($task,'text','main_keywords',stripslashes(html_entity_decode($main_keywords)),'input iput','main_keywords','200');?></td>
									</tr> 
									<tr><td><br/></td></tr>
							</table>
						</fieldset>	
						
						<div class="height40"><br/></div>
						
						<fieldset>
							<legend>Logo</legend>
								<table cellpadding="0" cellspacing="10" border="0" width="1000px">
						 			<tr><td><br/></td></tr>
									<tr><td><?= $mform->inputBox($task,'file','logo',$logo,'input iputshort','logo','50');?></td></tr>
									<tr><td><br/></td></tr>
									<tr><td>
									<? if(file_exists("./view/template1/images/".$oldlogo)){
											if(!empty($oldlogo)){
												echo "<img src=\"./view/template1/images/$oldlogo\" class=\"preview\">";
											}
										} ?>
									</td></tr>
							</table>
						</fieldset>	
						
						<div class="height40"><br/></div>
					    <div><input type="submit" name="save" value="Save" class="button2 roundbuttons"></div>
			 
						<div class="message"><p><? if(!empty($errmsg)){ foreach($errmsg as $errValue){ echo $errValue; } }?></p></div>
					</form> 
					</td>
				</tr>	 
			</table>
		</td> 
</tr>


</table>
 
  