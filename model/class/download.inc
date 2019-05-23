<?php
	class download extends coreMaintenance{
			
			private $uploadCounter;
			private $uploadType;
			private $downloadId;
			
			public function downloadForm($uploadCounter){
					 
					$downform 		= 	"";					 
					$mform 			= 	new formMaintenance();
					$task 			=	self::getCoreTask();
					$mod 			=	self::getCoreMod();
					$uploadType		=	self::getCoreType();
					
					$core					=	new coreFunctions();					
					// check if the module has category, postingdate, author, or image
					$modArray 				=	$core->getModuleFunction($uploadType);
					foreach ($modArray as $modIndex => $modVal) {
						$$modIndex 			=	$modVal;
						$mainArr 			=	explode('|', $$modIndex);
						$is_category 		=	$mainArr[1];  
						$is_postingdate		=	$mainArr[2];  
						$is_author			=	$mainArr[3];  
						$is_image 			=	$mainArr[4];
						$is_title 			=	$mainArr[5];
						$is_content			=	$mainArr[6];
						$is_tag 			=	$mainArr[7];	
					}
					
					$downform 	.= "<form method=\"POST\" enctype=\"multipart/form-data\">";
						$downform 	.= "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\">";
								$count 			=	0;
								$counter 		=	$uploadCounter;
								
								$downform 	.= "<input type=\"hidden\" name=\"hidden_counter\" value=\"$counter\">";
								$downform 	.= "<input type=\"hidden\" name=\"types\" value=\"$uploadType\">";
								
								$imtype			=	array();
								foreach ($_POST as $name => $value) {
									if ($count < $counter) {
										$fname			=	$_POST['uploader_' . $count . '_name'];
										$fname 			= 	preg_replace('/[^\w\._]+/', '_', $fname);
										$fname			=	str_replace(' ', '_', $fname);
										$postingdate	= 	date('m/d/Y');
										$publisheddate	= 	date('m/d/Y');
										$publishedtime	= 	date('H:i:s');
										$contents		=	"";
										$caption		=	"";

									  $downform 	.= "<tr>";
											$downform 	.= "<td colspan=\"2\" width=\"100%\" valign=\"top\">";
												$downform 	.= "<table cellpadding=\"0\" cellspacing=\"10\" border=\"0\" width=\"100%\">";
												 
												
												$downform 	.= "<tr>";
													$downform 	.= "<td valign=\"middle\">";
														$downform 	.= $mform->label('publisheddate','Publish Date','caption','req');
													$downform 	.= "</td>";
													$downform 	.= "<td>";
														$downform 	.= $mform->inputBox($task,'text','uploader_'.$count.'_pubdate',$publisheddate,'iputdate input','date_'.$count.'','10','readonly') ."&nbsp;". $mform->button($task,'trigger_'.$count.'','calbutton');
													$downform 	.= "</td>";
													
													$downform 	.= "<td valign=\"middle\">";
														$downform 	.= $mform->label('publishedtime','Publish Time','caption','req');
													$downform 	.= "</td>";
													$downform 	.= "<td>";
														$downform 	.=  $mform->inputBox($task,'text','uploader_'.$count.'_pubtime',$publishedtime,'iputdate input','time_'.$count.'','8');
													$downform 	.= "</td>";
												$downform 	.= "</tr>";
												$downform 	.= "<tr>";
													$downform 	.= "<td>";
														$downform 	.= $mform->label('filename','File','caption','req');
													$downform 	.= "</td>";	
													$downform 	.= "<td colspan=\"3\">";	
														$downform 	.= $mform->inputBox($task,'text','uploader_'.$count.'_tmpname',$fname,'iput input','filename_'.$count.'','255','readonly');
													$downform 	.= "</td>";	
												$downform 	.= "</tr>";	

											if($is_postingdate=='yes'){	
												$downform 	.= "<tr>";
													$downform 	.= "<td valign=\"middle\">";
														$downform 	.= $mform->label('postingdate','Posting Date','caption','req');
													$downform 	.= "</td>";
													$downform 	.= "<td colspan=\"3\">";
														$downform 	.= $mform->inputBox($task,'text','uploader_'.$count.'_postdate',$postingdate,'iputdate input','pdate_'.$count.'','10','readonly') ."&nbsp;". $mform->button($task,'ptrigger_'.$count.'','calbutton');
													$downform 	.= "</td>";
												$downform 	.= "</tr>";		
											}
											
												$downform 	.= "<tr>";
													$downform 	.= "<td>";
														$downform 	.= $mform->label('title','Caption','caption');
													$downform 	.= "</td>";	
													$downform 	.= "<td colspan=\"3\">";	
														$downform 	.= $mform->inputBox($task,'text','uploader_'.$count.'_caption',$caption,'iput input','caption_'.$count.'','255');
													$downform 	.= "</td>";	
												$downform 	.= "</tr>";	
												
											if($is_content=='yes'){	
												$downform 	.= "<tr>";
													$downform 	.= "<td valign=\"top\">";
														$downform 	.= $mform->label('description','Description','caption');
													$downform 	.= "</td>";
													$downform 	.= "<td colspan=\"3\">";
													$downform 	.= $mform->textarea($task,'uploader_'.$count.'_contents',stripslashes(html_entity_decode($contents)),'10','10','tinymce txtarea','innerelm'.$count.'');
													$downform 	.= "</td>";
												$downform 	.= "</tr>";	 
											}
												  
												$downform 	.= "<tr><td colspan=\"4\">&nbsp;</td></tr>";
												$downform 	.= "<tr><td colspan=\"4\"><hr/></td></tr>";
											   $downform 	.= "</table>";
										$downform 	.= "</td>";
										
									$downform 	.= "</tr>";
									$downform 	.= "<tr><td colspan=\"2\">&nbsp;</td></tr>";
																	 
				$downform 	.= "<script type=\"text/javascript\">
							  Calendar.setup(
								{
								  inputField  : \"date_$count\",
								  ifFormat    : \"%m/%d/%Y\",
								  button      : \"trigger_$count\"
								}
							  );
								</script> ";
								
							if($is_postingdate=='yes'){	
							$downform 	.= "<script type=\"text/javascript\">
								  Calendar.setup(
									{
									  inputField  : \"pdate_$count\",
									  ifFormat    : \"%m/%d/%Y\",
									  button      : \"ptrigger_$count\"
									}
								  );			 							  
							</script> ";
							}							
				$count++;
									}
								}
								 
				$downform 	.= "<tr><td colspan=\"2\">&nbsp;</td></tr>";
				$downform 	.= "<tr>
									<td align=\"left\"><input type=\"submit\" name=\"publish\" value=\"Publish\" class=\"button2 roundbuttons\"></td>
									<td align=\"right\">
										<span style=\"padding-right:80px\"><input type=\"submit\" name=\"savedraft\" value=\"Save Draft\" class=\"button2 roundbuttons \"></span>
										<input type=\"button\" name=\"cancel\" value=\"Cancel\" class=\"button2 roundbuttons\" onClick=\"location.href='index.php?mod=$mod&type=$uploadType'\">
									</td>
								</tr>
							</table>
							<div class=\"message\"><p>";
							if(!empty($errmsg)){ foreach($errmsg as $errValue){ $downform 	.= $errValue; } }
						$downform 	.= "</p></div>
						</form>";
						
						
						echo $downform;
						
						return $downform;
				}
				
				
				public function downloadFormEdit($downloadId){
					 
					$downform 		= 	"";
					$mform 			= 	new formMaintenance();
					$coreMain		= 	new coreMaintenance();
					$task 			=	self::getCoreTask();
					$mod 			=	self::getCoreMod();
					$uploadType		=	self::getCoreType();
					
					$core			=	new coreFunctions();					
					// check if the module has category, postingdate, author, or image
					$modArray 				=	$core->getModuleFunction($uploadType);
					foreach ($modArray as $modIndex => $modVal) {
						$$modIndex 			=	$modVal;
						$mainArr 			=	explode('|', $$modIndex);
						$is_category 		=	$mainArr[1];  
						$is_postingdate		=	$mainArr[2];  
						$is_author			=	$mainArr[3];  
						$is_image 			=	$mainArr[4];
						$is_title 			=	$mainArr[5];
						$is_content			=	$mainArr[6];
						$is_tag 			=	$mainArr[7];	
					}
					
					//Retrieve Values
					$arrayValues 		=	array('id', 'title','remarks', 'file', 'postingdate','publisheddate','publishedtime');
					$retArray 			= 	self::retrieveEntry("downloads", $arrayValues, "", "id='$downloadId'");
					foreach ($retArray as $retIndex => $retValue) {
						$$retIndex 		=	$retValue;
						$mainArr 		=	explode('|', $$retIndex);
						$did 			=	$mainArr[0];
						$title	 		=	$mainArr[1];
						$contents		=	$mainArr[2];
						$fname		 	=	$mainArr[3];
						$oldfname		=	$mainArr[3];
						$postingdate	=	$mainArr[4];
						$publisheddate	=	$mainArr[5];
						
						$postingdate 	=	$coreMain->retrieveDateFromDb($postingdate);
						$publisheddate 	=	$coreMain->retrieveDateFromDb($publisheddate);
						
						$publishedtime	=	$mainArr[6];						
					}
											 
						$currentdate	= 	date('m/d/Y');
						$currenttime	= 	date('H:i:s');
					
					$downform 	.= "<form method=\"POST\" enctype=\"multipart/form-data\">";
						$downform 	.= "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\">";
						 		 	  $downform 	.= "<tr>";
											$downform 	.= "<td colspan=\"2\" width=\"100%\" valign=\"top\">";
												$downform 	.= "<table cellpadding=\"0\" cellspacing=\"10\" border=\"0\" width=\"100%\">";
												  
												$downform 	.= "<tr>";
													$downform 	.= "<td valign=\"middle\">";
														$downform 	.= $mform->label('publisheddate','Publish Date','caption','req');
													$downform 	.= "</td>";
													$downform 	.= "<td>";
														$downform 	.= $mform->inputBox($task,'text','publisheddate',$publisheddate,'iputdate input','date','10','readonly') ."&nbsp;". $mform->button($task,'trigger','calbutton');
													$downform 	.= "</td>";
													
													$downform 	.= "<td valign=\"middle\">";
														$downform 	.= $mform->label('publishedtime','Publish Time','caption','req');
													$downform 	.= "</td>";
													$downform 	.= "<td>";
														$downform 	.=  $mform->inputBox($task,'text','publishedtime',$publishedtime,'iputdate input','time','8');
													$downform 	.= "</td>";
												$downform 	.= "</tr>";
												$downform 	.= "<tr>";
													$downform 	.= "<td>";
														$downform 	.= $mform->label('filename','File','caption','req');
													$downform 	.= "</td>";	
													$downform 	.= "<td colspan=\"3\">";	
														$downform 	.= $mform->inputBox($task,'text','fname',$fname,'iput input','filename','255','readonly');
													$downform 	.= "</td>";	
												$downform 	.= "</tr>";	

											if($is_postingdate=='yes'){	
												$downform 	.= "<tr>";
													$downform 	.= "<td valign=\"middle\">";
														$downform 	.= $mform->label('postingdate','Posting Date','caption','req');
													$downform 	.= "</td>";
													$downform 	.= "<td colspan=\"3\">";
														$downform 	.= $mform->inputBox($task,'text','postingdate',$postingdate,'iputdate input','pdate','10','readonly') ."&nbsp;". $mform->button($task,'ptrigger','calbutton');
													$downform 	.= "</td>";
												$downform 	.= "</tr>";		
											}
											
												$downform 	.= "<tr>";
													$downform 	.= "<td>";
														$downform 	.= $mform->label('title','Caption','caption');
													$downform 	.= "</td>";	
													$downform 	.= "<td colspan=\"3\">";	
														$downform 	.= $mform->inputBox($task,'text','title',$title,'iput input','caption','255');
													$downform 	.= "</td>";	
												$downform 	.= "</tr>";	
												
											if($is_content=='yes'){	
												$downform 	.= "<tr>";
													$downform 	.= "<td valign=\"top\">";
														$downform 	.= $mform->label('description','Description','caption');
													$downform 	.= "</td>";
													$downform 	.= "<td colspan=\"3\">";
													$downform 	.= $mform->textarea($task,'contents',stripslashes(html_entity_decode($contents)),'10','10','tinymce txtarea','innerelm');
													$downform 	.= "</td>";
												$downform 	.= "</tr>";	 
											}	
												  
												$downform 	.= "<tr><td colspan=\"4\">&nbsp;</td></tr>";
												
												$downform 	.= "<tr>";
													$downform 	.= "<td valign=\"top\">";
														$downform 	.= "<input type=\"checkbox\" name=\"ischeck\" value=\"yes\" id=\"ischeck\">";
														$downform 	.= $mform->label('reupload','Re-upload File ?','caption');
													$downform 	.= "</td>";
													$downform	.= "<td  colspan=\"3\" id=\"uploadfile\">";
													$downform 	.= "<input type=\"file\" name=\"fileupload\" id=\"uploadvalue\" onChange=\"changeFileCaption()\"/>";
													$downform 	.= "&nbsp;&nbsp;&nbsp;<input type=\"text\" readonly id=\"uploadcaption5\" style=\"width:100%; border:none; color:#ff0000; font-weight:bold; font-size:8pt; font-family:arial;\">";
													$downform 	.= "</td>";
												$downform 	.= "</tr>";	
												
												$downform 	.= "<tr><td colspan=\"4\">&nbsp;</td></tr>";
												
												$downform 	.= "</table>";
										$downform 	.= "</td>";										
									$downform 	.= "</tr>";
									$downform 	.= "<tr><td colspan=\"2\">&nbsp;</td></tr>";
																	 
				$downform 	.= "<script type=\"text/javascript\">
							  Calendar.setup(
								{
								  inputField  : \"date\",
								  ifFormat    : \"%m/%d/%Y\",
								  button      : \"trigger\"
								}
							  );
								</script> ";
								
							if($is_postingdate=='yes'){	
							$downform 	.= "<script type=\"text/javascript\">
								  Calendar.setup(
									{
									  inputField  : \"pdate\",
									  ifFormat    : \"%m/%d/%Y\",
									  button      : \"ptrigger\"
									}
								  );			 							  
							</script> ";
							}
							
							
							$downform 	.= "<script type=\"text/javascript\"> 
												function changeFileCaption(){
													var test5	= document.getElementById('uploadvalue').value;
													document.getElementById('uploadcaption5').value = test5;
												} 
											</script>";	
							
							$downform 	.= "<script type=\"text/javascript\">
												$(document).ready(
													function()
													{			 
														$(\"#uploadfile\").hide();	
														$(\"#ischeck\").click(function(){				 
																	$(\"#uploadfile\").toggle(); 						
															}
														) 
													}
												);
											</script>";
		 
								 
				$downform 	.= "<tr><td colspan=\"2\">&nbsp;</td></tr>";
				$downform 	.= "<tr>
									<td align=\"left\"><input type=\"submit\" name=\"publish\" value=\"Publish\" class=\"button2 roundbuttons\"></td>
									<td align=\"right\">
										<span style=\"padding-right:80px\"><input type=\"submit\" name=\"savedraft\" value=\"Save Draft\" class=\"button2 roundbuttons \"></span>
										<input type=\"button\" name=\"cancel\" value=\"Cancel\" class=\"button2 roundbuttons\" onClick=\"location.href='index.php?mod=$mod&type=$uploadType'\">
									</td>
								</tr>
							</table>
							<div class=\"message\"><p>";
							if(!empty($errmsg)){ foreach($errmsg as $errValue){ $downform 	.= $errValue; } }
						$downform 	.= "</p></div>
						</form>";
						 
						echo $downform; 
						return $downform;
				}
				
				

				public function archiveDownload($yr){	
					$out			=	"";
					$cType			=	self::getCoreType();				
					$ispreview		=	self::getCorePreview();
					$dbTable		=	self::getCoreDbTable();
					$stats			=	($ispreview=='true') ? "" : " AND status='published' "; 
					$cond			=	"type='$cType' $stats AND ((TIMEDIFF(publishedtime,CURTIME())>='00:00:00' AND (SELECT DATEDIFF(publisheddate,CURDATE())<0) OR ((SELECT DATEDIFF(publisheddate,CURDATE())<=0) AND (SELECT HOUR(publishedtime)<=HOUR(CURTIME())))))";
					$cond			.= 	"GROUP BY YEAR(publisheddate) ORDER BY YEAR(publisheddate) DESC;";
					$arrayValues3 	=	array('YEAR(publisheddate) as year');
					$retArray3 		= 	self::retrieveEntry($dbTable, $arrayValues3, "", "$cond");
					foreach ($retArray3 as $retIndex => $retValue) {
						$$retIndex 		=	$retValue;
						$mainArr 		=	explode('|', $$retIndex);						
						$year	 		=	$mainArr[0];	
						$cssstyle		=	($year==$yr) ? "yearstyleselected" : "yearstyle";
						$out .= "<dt>$year</dt>";
						$out .= "<dd>
									<ul>";
										$out .= self::archiveMonthDownload($year);
							$out .= "</ul>	
								</dd>"; 
					} 
					return $out; 
				}
				
				public function archiveMonthDownload($yr){
					$out			=	"";
					$cType			=	self::getCoreType();				
					$months			=	self::getCoreMonth();				
					$selectedyear	=	self::getCoreYear();				
					$ispreview		=	self::getCorePreview();
					$dbTable		=	self::getCoreDbTable();
					$stats			=	($ispreview=='true') ? "" : " AND status='published' "; 
					
					$cond			=	"type='$cType' AND YEAR(publisheddate)='$yr' $stats AND ((TIMEDIFF(publishedtime,CURTIME())>='00:00:00' AND (SELECT DATEDIFF(publisheddate,CURDATE())<0) OR ((SELECT DATEDIFF(publisheddate,CURDATE())<=0) AND (SELECT HOUR(publishedtime)<=HOUR(CURTIME())))))";
					$cond			.= 	"GROUP BY MONTHNAME(publisheddate) ORDER BY MONTH(publisheddate) DESC;";
					$arrayValues3 	=	array('MONTHNAME(publisheddate) as month','YEAR(publisheddate) as year');
					$retArray3 		= 	self::retrieveEntry($dbTable, $arrayValues3, "", "$cond");
					foreach ($retArray3 as $retIndex => $retValue) {
						$$retIndex 		=	$retValue;
						$mainArr 		=	explode('|', $$retIndex);						
						$mo		 		=	$mainArr[0];
						$year		 	=	$mainArr[1];						
						$cssstyle		=	(($selectedyear==$yr) && ($months==$mo)) ? "orange" : "grey";
												
						$out .= "<li><a href=\"index.php?mod=$cType&year=$yr&month=$mo\">$mo</a></li>";					 
					}
					return $out; 
				}	
				
				
	}
?>
