	<tr>
		<td colspan="2">
			<form method="POST"  onsubmit="return ValidateForm(this, 'delAnn[]');">
			<table width="100%" cellpadding="0" cellspacing="0">					
					<tr>
						<td colspan="4">
							<table cellpadding="0" cellspacing="0" border="0" width="100%">
								<tr valign="middle">															 
									<td class="verdana10"></td>									 
									<td  align="right" class="verdana10"><?= $log->showItems();?></td>
								</tr>
							</table>
						</td>
					</tr> 
					<tr><td colspan="4">&nbsp;</td></tr>
					<tr valign="middle">
						<td width="5%" align="center" class="table_line1_left"></td>
						<td width="25%" class="table_line1_left paddleft">&nbsp;USERNAME</td>							
						<td width="20%" class="table_line1_left paddleft">&nbsp;TIMESTAMPS</td>
						<td width="50%" class="table_line1_left_right paddleft">&nbsp;ACTIVITY DONE</td>
					</tr>
					<tr valign="middle"><td class="bar" colspan="4"></td></tr>		
			<?php
				$bgcolor = "";
				if($num_rows>0){	
					for($i=0;$i<$num_rows;$i++){
					
						$ids 				= mysql_result($result, $i, "id");
						$username		 	= mysql_result($result, $i, "username");
						$timestamps		 	= mysql_result($result, $i, "timestamps");
						$activity		 	= mysql_result($result, $i, "activitydone");
						$modtype 			= $type;						  
						
							
							$bgcolor = ($bgcolor != "#FFFFFF")? "#FFFFFF" : "#EFEFEF"; 							
							echo "<tr bgcolor=\"$bgcolor\">";								 												
							echo '	<td align="center" class="table_line2_left">'.$ids.'</td>
									<td class="paddleft table_line2_left " valign="top" align="left">'.$username.'</td>
									<td class="paddleft table_line2_left " valign="top" align="left">'.$timestamps.'</td>
									<td class="paddleft table_line2_left_right" valign="top" align="left">&nbsp;'.rep_under($activity).'</td>';
							echo '</tr>';
					}
				}else{
					echo '<tr valign="middle" width="20px"><td colspan="4" class="table_line2_left table_line2_left_right" align="center"><p class="errmsg">NO RECORDS FOUND</p></td></tr>';
				}
				echo '<tr valign="middle"><td class="bar" colspan="4"></td></tr>		
					<tr valign="middle">
						<td class="table_footer_left" align="center" colspan="2">&nbsp;'.$PAGINATION_INFO.'</td>
						<td class="table_footer_left_right" align="center" colspan="2"><span class="link">'.$PAGINATION_LINKS.'</span></td>';		
				?>		
				 
			</table>
			</form>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
</table>
