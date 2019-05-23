<table width="98%" cellpadding="0" cellspacing="0">
	<tr>
			<td valign="top" height="400">
				<table border="0" class="form" cellspacing="2" cellpadding="2" width="100%" style="padding:10px">
		
					<tr>
						<td valign="top" align="left" colspan="6" width="100%">				
							<form method="post" name="sel" style="display: inline; margin: 0;" ENCTYPE="multipart/form-data">
							<table border="0" cellspacing="2" cellpadding="2" width="100%">					
																						

								<tr><td height="5"></td></tr>									
															
								<tr><td  colspan="2" align="left"  class="header"><img src="<?=AP_MOD_USERLOGSIMAGES;?>article_edit.gif" alt="ADMIN USER LOGS">&nbsp; ADMIN USER LOGS</td></tr>	
								<tr><td height="25"></td></tr>
								
							</table>
							</form>	
						</td>
					</tr>	
					
					<tr><td height="20"></td></tr>	
					<script>
							function reload_rows(rows){
								this.location.href = "index.php?mod=adminlogs&rownum="+rows;
							}
							
							function hide_div(divid){
							
								document.getElementById(divid).style.display="none";
							}
							
							function show_div(divid){
								document.getElementById(divid).style.display="";
							}
						</script>
					<tr>
						<td>
							<form method="POST"  onsubmit="return ValidateForm(this, 'delAnn[]');">
								<table width="100%" cellpadding="0" cellspacing="0">
									<tr valign="middle" width="22PX">
										<td colspan="2" align="left" class="caption">
											<span class="caption">No of Rows:</span> &nbsp; <select name="rowselect" class="iselect2" onchange="reload_rows(this.value)">
									<option <?=($rownum==10)?"selected":""?> value='5'>5</option>
									<option <?=($rownum==10)?"selected":""?> value='10'>10</option>
									<option <?=($rownum==25)?"selected":""?> value='25'>15</option>
									<option <?=($rownum==25)?"selected":""?> value='25'>20</option>
									<option <?=($rownum==25)?"selected":""?> value='25'>25</option>
									<option <?=($rownum==50)?"selected":""?> value='50'>50</option>
											</select>
										</td>
									</tr>	
									<tr height="25px"><td></td></tr>	
									<tr valign="middle" width="22PX">
										
										<td width="50%" class="table_line1_left_right">USERNAME</td>																																	
										<td width="30%" class="table_line1_left_right">ACTIVITY</td>																																	
										<td width="20%" class="table_line1_left_right">DATE</td>																																	
									</tr>
									<tr valign="middle"><td class="bar" colspan="3"></td></tr>		
							
								<?php
										if($num_rows>0){	
											for($i=0;$i<$num_rows;$i++){												
												$ids			= mysql_result($result, $i, "id");									
												$username			= mysql_result($result, $i, "userName");									
												$activity			= mysql_result($result, $i, "activity");									
												$datetime			= mysql_result($result, $i, "datetime");									
												
												echo '<tr>';
													echo '<td class="table_line2_left_right">&nbsp;'.$username.'</td>';
													echo '<td class="table_line2_left_right">&nbsp;'.$activity.'</td>';
													echo '<td class="table_line2_left_right">&nbsp;'.$datetime.'</td>';
													
												echo '</tr>';
											}
										}else{
											echo '<tr valign="middle"><td colspan="2" class="table_line2_left table_line2_left_right" align="center"><p class="errmsg">NO LOGS FOUND</p></td></tr>';
										}
										
								
										echo '<tr valign="middle"><td  class="bar"  colspan="3"></td></tr>';		
											echo  '<tr valign="middle">
												<td class="table_footer_left" align="center">&nbsp;'.$nav_info.'</td>
												<td colspan="2" class="table_footer_left_right"  align="center">&nbsp;<span class="link">'.$nav_links.'</span></td></tr>';												
															
									
									?>		
								</table>
							</form>							
						</td>
					</tr>
					
					
					
					

					
				</table>
			</td>
		</tr>
</table>	
