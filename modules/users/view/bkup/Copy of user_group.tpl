
<table width="98%" class="form" align="center" cellpadding="10" cellspacing="10">
	<tr>
		<td>
		<form method="POST"  ENCTYPE="multipart/form-data">
			<table width="100%" cellpadding="2" cellspacing="0">
				<tr><td  colspan="2" align="left" class="header"><?=strtoupper(rep_under($type))?></td></tr>	 		 			
				<tr><td colspan="2">&nbsp;</td></tr>			
				 
				<tr>
					<td valign="top"><?= $mform->label('groupname','Group Name :','caption','req')?></td>
					<td><?= $mform->inputBox($task,'text','groupname',$groupname,'inputboxes width300','groupname','200');?></td>
				</tr>
								 		
				<tr>
					<td valign="top"><?= $mform->label('description','Description :','caption')?></td>
					<td><?= $mform->inputBox($task,'text','description',$description,'inputboxes width300','description','200');?></td>
				</tr>
				
				<tr>
					<td valign="top"><?= $mform->label('groupaccess','Group Access :','caption','req')?></td>						
					<td> 
					<div><br/></div>
	<?
	$arrayValues = array('id','modulename','modulecaption');
	$retArray = $recon->retrieveEntry("modules", $arrayValues, "", "stat='active' and remarks!='' ORDER BY modulecaption");
        foreach ($retArray as $retIndex => $retValue) {
            $$retIndex 			= $retValue;
            $mainArr 			= explode('|', $$retIndex);
            $modid 				= $mainArr[0];
            $modules			= $mainArr[1];
            $modcap				= $mainArr[2];?>
			
			
			<?   		$checked = "";
						if($task=='edit'){
						$retrievegroupmodule	= array();							
						$retrieveaccessmodule	= array();							
						$taccess	= array();							
						//Retrieve Values USER GROUP ACCESS	
						$arrayValues = array('id','groupid','moduleid','accessid');
						$retArray = $recon->retrieveEntry("groupmoduleaccess", $arrayValues, "", "groupid='$sid'");
							foreach ($retArray as $retIndex => $retValue) {
								$$retIndex 					= $retValue;
								$mainArr 					= explode('|', $$retIndex);
								$grid 						= $mainArr[0];
								$groupid					= $mainArr[1];
								$modulelists				= $mainArr[2];
								  $accessid					= $mainArr[3];
								$retrievegroupmodule[] 		= $modulelists;			
								$retrieveaccessmodule[] 	= $accessid;		

								$taccess[] = explode(',',$accessid);
								
									if($modid==$modulelists){ 
										echo "<div><input type=\"hidden\" name=\"modgroupid[]\" value=\"$modid\"></div>";
									}							
							}  
						 
						$checked = in_array($modid, $retrievegroupmodule) ? "checked" : ""; 						
				?>
				
				<div class="caption"><input type="checkbox" name="modulelist[]" <?=$checked?> value="<?=$modid?>"><?=$modcap?></div>
		<?}else{?>			
				<div class="caption"><input type="checkbox" name="modulelist[]" <?=$checked?> value="<?=$modid?>"><?=$modcap?></div>
		<?}?>
		<div><br/></div>	
		<div>
						<?	 
						  	/*$checked2 		= "";
							$arrayValues2 	= array('id','name');
							$retArray2 		= $recon->retrieveEntry("reference", $arrayValues2, "", "ref_name='user_access'");
						
										foreach ($retArray2 as $retIndex => $retValue) {
											$$retIndex 	= $retValue;
											$mainArr 		= explode('|', $$retIndex);
											$uid 			= $mainArr[0];
											$modtask		= $mainArr[1];																				 
											
											$checked2 = $core->checkIfEnabled($sid, $modid, $uid);										 
											
									?>
						<span class="caption">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="<?=$modid?>_moduletask[]" <?=$checked2?> value="<?=$uid?>"><?=$modtask?></span>				
										<?	 	
										 	} 
						 	 ?>
			</div>
			<div><br/><hr/><br/></div>				
	<?	*/ }?>
				 
				 
				</td></tr>	
				 
				<tr><td colspan="2">&nbsp;</td></tr>	
				
			<?if((strtolower($task)=='create') || (strtolower($task)=='edit') ){ ?>				
				<tr><td colspan="2"><input type="submit" class="button2 roundbuttons" name="Save" value="Save"></td></tr>				
			<?}else{?>					
				<tr><td colspan="2"><input type="button" class="button2 roundbuttons"  onclick="parent.parent.GB_hide();"  value="Close"></td></tr>
			<?}?>	
			</table>
			<div class="message"><p><? if(!empty($errmsg)){ foreach($errmsg as $errValue){ echo $errValue; } }?></p></div>			
		</form>
		</td>
	</tr>	
</table>



