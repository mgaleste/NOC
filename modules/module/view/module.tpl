<table width="100%" class="form" cellpadding="10" cellspacing="10" border="0">
	<tr><td  colspan="2" align="left" class="header"><?=strtoupper(rep_under($type))?></td></tr>	 
	
	<tr>
		<td  colspan="2" align="left">
	 		<form method="POST" enctype="multipart/form-data">
				<table cellpadding="0" cellspacing="10" border="0" width="100%">
				
						<tr>
							<td valign="middle"><?= $mform->label('modulename','Module Name','caption','req')?></td>
							<td><select name="modulename" class="iselect" id="modulename">
									<option value="">- Select One -</option>
									<? $query = "SELECT *  FROM modules WHERE modulestat='back' AND stat='active' AND modulegroup='Modules' GROUP BY modulename ";
									 echo $mform->test($query,$modulename,"modulename","modulename");									 
									 ?>													
								</select> 
							</td>
						</tr>
						<tr>
							<td valign="middle"><?= $mform->label('modulecaption','Module Caption','caption','req')?></td>
							<td><?= $mform->inputBox($task,'text','modulecaption',$modulecaption,'input iputshort','modulecaption','200');?></td>
						</tr>
						
						
						
				 
						<tr class="size_config">
							<td valign="middle"><?= $mform->label('max_main_height','Max Main Height','caption','req')?></td>
							<td><?= $mform->inputBox($task,'text','max_main_height',$max_main_height,'input iputshort','max_main_height','200');?></td>
						</tr>
		
						<tr class="size_config">
							<td valign="middle"><?= $mform->label('max_main_width','Max Main Width','caption','req')?></td>
							<td><?= $mform->inputBox($task,'text','max_main_width',$max_main_width,'input iputshort','max_main_width','200');?></td>
						</tr>
						
						<tr class="size_config">
							<td valign="middle"><?= $mform->label('max_thumb_height','Max Thumb Height','caption','req')?></td>
							<td><?= $mform->inputBox($task,'text','max_thumb_height',$max_thumb_height,'input iputshort','max_thumb_height','200');?></td>
						</tr>
						
						<tr class="size_config">
							<td valign="middle"><?= $mform->label('max_thumb_width','Max Thumb Width','caption','req')?></td>
							<td><?= $mform->inputBox($task,'text','max_thumb_width',$max_thumb_width,'input iputshort','max_thumb_width','200');?></td>
						</tr>
						
						<tr class="size_config">
							<td valign="middle"><?= $mform->label('max_kb','Max Kb','caption','req')?></td>
							<td><?= $mform->inputBox($task,'text','max_kb',$max_kb,'input iputshort','max_kb','200');?></td>
						</tr>
					 
						
						<tr id="is_category_config">
							<td valign="middle"><?= $mform->label('is_category','Is Category','caption')?></td>
							<td>
								<?= $mform->inputBox($task,'radio','is_category','yes','','is_category'); ?>Yes	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?= $mform->inputBox($task,'radio','is_category','no','','is_category'); ?>No	
							</td>
						</tr>
						
						<tr id="is_postingdate_config">
							<td valign="middle"><?= $mform->label('is_postingdate','Is Posting Date','caption')?></td>
							<td>
								<?= $mform->inputBox($task,'radio','is_postingdate','yes','','is_postingdate'); ?>Yes	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?= $mform->inputBox($task,'radio','is_postingdate','no','','is_postingdate'); ?>No	
							</td>
						</tr>
						
						<tr id="is_author_config">
							<td valign="middle"><?= $mform->label('is_author','Is Author','caption')?></td>
							<td>
								<?= $mform->inputBox($task,'radio','is_author','yes','','is_author'); ?>Yes	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?= $mform->inputBox($task,'radio','is_author','no','','is_author'); ?>No	
							</td>
						</tr>
						
						<tr id="is_image_config">
							<td valign="middle"><?= $mform->label('is_image','Is Image','caption')?></td>
							<td>
								<?= $mform->inputBox($task,'radio','is_image','yes','','is_image'); ?>Yes	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?= $mform->inputBox($task,'radio','is_image','no','','is_image'); ?>No	
							</td>
						</tr>

						<tr id="is_title_config">
							<td valign="middle"><?= $mform->label('is_title','Is Title','caption')?></td>
							<td>
								<?= $mform->inputBox($task,'radio','is_title','yes','','is_title'); ?>Yes	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?= $mform->inputBox($task,'radio','is_title','no','','is_title'); ?>No	
							</td>
						</tr>
						
						<tr id="is_content_config">
							<td valign="middle"><?= $mform->label('is_content','Is Content','caption')?></td>
							<td>
								<?= $mform->inputBox($task,'radio','is_content','yes','','is_content'); ?>Yes	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?= $mform->inputBox($task,'radio','is_content','no','','is_content'); ?>No	
							</td>
						</tr>

						<tr id="is_tag_config">
							<td valign="middle"><?= $mform->label('is_tag','Is Tag','caption')?></td>
							<td>
								<?= $mform->inputBox($task,'radio','is_tag','yes','','is_tag'); ?>Yes	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?= $mform->inputBox($task,'radio','is_tag','no','','is_tag'); ?>No	
							</td>
						</tr>
						
						<tr id="is_gallery_config">
							<td valign="middle"><?= $mform->label('is_gallery','Is Gallery','caption')?></td>
							<td>
								<?= $mform->inputBox($task,'radio','is_gallery','yes','','is_gallery'); ?>Yes	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?= $mform->inputBox($task,'radio','is_gallery','no','','is_gallery'); ?>No	
							</td>
						</tr>

						<tr>
							<td valign="middle"><?= $mform->label('imagepath','Module Icon Filename','caption')?></td>
							<td><?= $mform->inputBox($task,'text','imagepath',$imagepath,'input iputshort','imagepath','200');?></td>
						</tr>
 
						<tr><td colspan="2">&nbsp;</td></tr>						
						<tr><td align="left" colspan="2"><input type="submit" name="save" value="Save" class="button2 roundbuttons"></td></tr>
					</table>
					<div class="message"><p><? if(!empty($errmsg)){ foreach($errmsg as $errValue){ echo $errValue; } }?></p></div>
			</form>
		</td>
	</tr>	 
</table>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript">
		$(document).ready(function(){
				$('.size_config').hide();
				$('#is_gallery_config').hide();
				$('#is_tag_config').hide();
				$('#is_content_config').hide();
				$('#is_title_config').hide();
				$('#is_image_config').hide();
				$('#is_author_config').hide();
				$('#is_postingdate_config').hide();
				$('#is_category_config').hide();
				
				checkModName();
						
				$('#modulename').change(function(e){
						e.preventDefault();
						checkModName();
				});
		});
		
		function checkModName(){
					var modname = $('#modulename').val();
					if(modname=="about" || modname=="news" || modname=="links" || modname=="jobs"){
						$('.size_config').show();
					}else{
						$('.size_config').hide();			
					}	
					  
					if(modname=="about"){
						$('#is_gallery_config').show();
						$('#is_tag_config').show();
						$('#is_content_config').show();
						$('#is_title_config').show();
						$('#is_image_config').show();
						$('#is_category_config').show();
						$('#is_author_config').hide();
						$('#is_postingdate_config').hide();						
					}else
					if(modname=="news"){
						$('#is_gallery_config').show();
						$('#is_tag_config').show();
						$('#is_content_config').show();
						$('#is_title_config').show();
						$('#is_image_config').show();
						$('#is_category_config').show();
						$('#is_author_config').hide();
						$('#is_postingdate_config').hide();						
					}else					
					if(modname=="links"){						
						$('#is_tag_config').show();
						$('#is_content_config').show();
						$('#is_title_config').hide();
						$('#is_image_config').hide();
						$('#is_category_config').hide();
						$('#is_author_config').hide();
						$('#is_postingdate_config').hide();
						$('#is_gallery_config').hide();
					}else					
					if(modname=="jobs"){						
						$('#is_tag_config').show();
						$('#is_content_config').show();
						$('#is_title_config').show();
						$('#is_image_config').show();
						$('#is_category_config').show();
						$('#is_author_config').show();
						$('#is_postingdate_config').show();
						$('#is_gallery_config').hide();
					}else{						
						$('#is_tag_config').hide();
						$('#is_content_config').hide();
						$('#is_title_config').hide();
						$('#is_image_config').hide();
						$('#is_author_config').hide();
						$('#is_postingdate_config').hide();
						$('#is_category_config').hide();
						$('#is_gallery_config').hide();
					} 
		}
		
		
</script>
