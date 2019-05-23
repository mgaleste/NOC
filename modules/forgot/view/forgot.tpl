<form method="POST">
	<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
		<tr><td><br></td></tr>
		<tr><td align="center" class="req"><?=$errmsg;?></td></tr>	
		<tr><td height="5" colspan="2"></td></tr>		
		<tr>
			<td>
				<table width="330px" align="center" border="0" cellpadding="3" cellspacing="0">
				 		<tr><td height="5" colspan="2"></td></tr>					
						<tr>
							<td class="caption" align="left" width="110px">Username :</td>
							<td align="right"><input type="text" name="user_name" style="width:190px; font-family:helvetica; font-size:16px" class="inputboxes"></td>
						</tr>						
						<tr>
							<td class="caption" align="left">Email Address :</td>
							<td align="right"><input type="text" class="inputboxes" style="width:190px; font-family:helvetica; font-size:16px" name="emailaddress" ></td>
						</tr>
						<tr><td height="10" colspan="2"></td></tr>	   
						<tr><td align="right" colspan="2"><input type="submit" name="Send" class="roundbuttons button" value="Send"></td></tr>							
						<tr><td height="30" colspan="2"></td></tr>	       		
						<tr><td colspan="2" align="right" class="forgot"><a href="index.php">Log In</a></td></tr>	   
				</table>
			</td>
		</tr>
	</table>
</form>
