<?
	function pageMod(){	
		$snipp 	= ""; 			
		$snipp .= "<form method=\"POST\" enctype=\"multipart/form-data\">
						<table cellpadding=\"0\" cellspacing=\"10\" border=\"0\" width=\"100%\">
							<tr>
								<td colspan=\"2\">
									<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
										<tr>
											<td valign=\"top\">".$mform->label('publisheddate','Publish Date :','caption','req')."</td>
											<td>".$mform->inputBox($task,'text','publisheddate',$publisheddate,'iputdate input','date','5','readonly');?>&nbsp;<?= $mform->button($task,'trigger','calbutton')."</td>
											<td valign=\"top\">".$mform->label('publishedtime','Publish Time :','caption','req')."</td>
											<td>".$mform->inputBox($task,'text','publishedtime',$publishedtime,'iputdate input','','8','')."</td>
										</tr>
									</table>
								</td>
							</tr>							
							<tr><td colspan=\"2\"><hr/></td></tr>
							<tr><td>&nbsp;</td></tr>";
				if($is_image=='yes'){
				$snipp  .= "<tr>
								<td valign=\"top\">".$mform->label('title','Image :','caption')."</td>
								<td><input type=\"file\" name=\"images5\" id=\"images5\"  onChange=\"changeImageCaption()\" class=\"iput3\"/>&nbsp;&nbsp;&nbsp;<input type=\"text\" readonly id=\"imagecaption5\" style=\"border:none; color:#ff0000; font-weight:bold; font-size:8pt; font-family:arial;\"></td> 
							</tr>						
							<tr><td><div id='preview'>";
						if( $task=='edit' &&  file_exists("./../uploads/$type/thumb/".$oldimagepath)){						 
								if(!empty($oldimagepath)){
						$snipp .= "<img src=\"./../uploads/$type/thumb/$oldimagepath\" class=\"preview\">";
								}else{
						$snipp .= "<div class=\"preview\" style=\"width:100px; height:100px;text-align:center\">Image Here</div>";
								}		
						}
					$snipp .= "</div></td></tr>";	
				}
				if($is_category=='yes'){
				$snipp .= "<tr>
							<td valign=\"top\" width=\"200px\">".$mform->label('category','Category :','caption','req')."</td>
							<td>
								<select name=\"catid\" id=\"catid\" class=\"iselect\">
									<option value=\"0\">- Select One -</option>";
									display_child('0','0',$type,$catid);
					 $snipp .= "</select>
								<a onclick=\"popupForm('category', '', '$type');\" href=\"javascript:void(0);\" class=\"linkcategory\">ADD CATEGORY</a>
							</td>
						</tr>";
				}
				if($is_author=='yes'){
			 $snipp .= "<tr>
							<td valign=\"top\">".$mform->label('author','Author :','caption')."</td>
							<td>".$mform->inputBox($task,'text','author',$author,'iput input','author','100')."</td>
						</tr>";
				}
				
				if($type=='testimonials'){
			  $snipp .= "<tr>
							<td valign=\"top\">".$mform->label('remarks','Designation','caption')."</td>
							<td>".$mform->inputBox($task,'text','remarks1',$remarks1,'iput input','remarks1','250')."</td>
						</tr>";
				}	
					
				if($is_title=='yes'){
			  $snipp .= "<tr>
								<td valign=\"top\">".$mform->label('title','Title :','caption','req')."</td>
								<td>".$mform->inputBox($task,'text','title',$title,'input iput','title','200')."</td>
						</tr>";
				}
				
				if($is_content=='yes'){
				$snipp .= "<tr>
								<td valign=\"top\">".$mform->label('contents','Contents','caption','req')."</td>
								<td valign=\"top\">".$mform->textarea($task,'contents',stripslashes(html_entity_decode($contents)),'40','20','tinymce txtarea','innerelm1')."</td>
						   </tr>";
				}
				
				if($is_tag=='yes'){
				$snipp .= "<tr>
								<td valign=\"top\"  width=\"200px\">".$mform->label('tags','Tags :','caption')."<br/><span class=\"page_tag\">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard</span></td>
								<td valign=\"top\">".$mform->textarea($task,'tags',$tags,'5','100','iarea')."</td>
							</tr>";
                  }
						 
				$snipp .= "<tr><td colspan=\"2\">&nbsp;</td></tr>";
						
				$snipp .= "<tr>
								<td align=\"left\"><input type=\"submit\" name=\"publish\" value=\"Publish\" class=\"button2 roundbuttons\" onclick=\"change()\"></td>
								<td align=\"right\">
								<span style=\"padding-right:80px\"><input type=\"submit\" name=\"savedraft\" value=\"Save Draft\" class=\"button2 roundbuttons \" onclick=\"change()\"></span>
								<input type=\"button\" name=\"cancel\" value=\"Cancel\" class=\"button2 roundbuttons\" onClick=\"location.href='index.php?mod=$mod&type=$type'\">								
							</td>
						</tr>
					</table>
					<div class=\"message\"><p>".if(!empty($errmsg)){ foreach($errmsg as $errValue){ echo $errValue; } }."</p></div>
			</form>";
		 
$snipp .= "<script type=\"text/javascript\">
			  Calendar.setup(
			    {
			      inputField  : \"date\",  // ID of the input field
			      ifFormat    : \"%Y-%m-%d \",        // the date format
			      button      : \"trigger\"           // ID of the button
			    }
			  );
</script> 
 
 
<script type=\"text/javascript\" > 
		function changeImageCaption(){	 
					var test5	= document.getElementById('images5').value;
					document.getElementById('imagecaption5').value = test5;
		} 		
</script>		";


return $snipp;
}


?>