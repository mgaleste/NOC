
<table width="98%" class="form" align="center" cellpadding="10" cellspacing="10">
	<tr>
		<td>
		<form method="POST"  ENCTYPE="multipart/form-data">
			<table width="100%" cellpadding="2" cellspacing="0">
				<tr><td colspan="2">&nbsp;</td></tr>			
				 
				<tr>
					<td valign="top"><?= $mform->label('groupCode','Group Code ','caption','req')?></td>
					<td><?= $mform->inputBox($taskView,'text','groupCode',$groupCode,'flat_input '.$ro_class.'','groupname','200','readonly');?></td>
				</tr>
								 		
				<tr>
					<td valign="top"><?= $mform->label('groupName','Group Name ','caption')?></td>
					<td><?= $mform->inputBox($taskView,'text','groupName',$groupName,'flat_input '.$ro_class.'','description','200',$isReadOnly);?></td>
				</tr>
				
				<tr>
					<td valign="top"><?= $mform->label('groupaccess','Group Access ','caption','req')?></td>						
					<td> 
					<div><br/></div>
	<?
	$arrayValues = array('moduleCode','moduleName','moduleCaption');
	$retArray = $recon->retrieveCustomQuery("SELECT m.moduleCode, m.moduleName, m.moduleCaption, t.taskName, t.taskCode FROM modules m JOIN modules_tasks mt USING(moduleCode) JOIN tasks t USING(taskCode) WHERE m.moduleStat='back' and m.stat='active' and m.remarks!='' ORDER BY m.moduleCaption,t.taskCode");
        $currentModId=$previousModId=0;
        $count=0;
        foreach ($retArray as $retIndex => $retValue) {
            $$retIndex 			= $retValue;
            $mainArr 			= explode('|', $$retIndex);
            $modid 				= $mainArr[0];
            $modules			= $mainArr[1];
            $modcap				= $mainArr[2];
            $taskName				= $mainArr[3];
            $taskCode				= $mainArr[4];
			$checked			= "";
			
			if($task=='edit' || $task=='view'){
				$checked = in_array($modid, $usersGroupRetrieval) ? "checked" : ""; 			
				$disabled = ($task=='view') ? "disabled" : "";			
			} 
			$currentModId = $modid;
			if($currentModId!=$previousModId){
				/**/
			$groupArrayValues 			=	array('taskCode','moduleCode');
			$usergroups->setUserCond("groupCode='$sid' AND moduleCode='$modid'");
			$usergroups->setUserTable("group_modules_tasks");
			$usergroups->setUserArray($groupArrayValues);    
			$usersTaskGroupRetrieval 		=	$usergroups->userGroupTaskAccessRetrieveInfo();
			/**/
				if($count){
					echo "<div><br/></div>";
				}
				
			?>
			<div><input <?=$disabled?> type="checkbox" name="modulelist[]" <?=$checked?> value="<?=$modid?>" onclick="toggleModule(this.value,this.checked)"><?=$modcap?></div>
			<?
			
			}
			$taskChecked =  in_array($taskCode,$usersTaskGroupRetrieval)?  "checked" : ""; 
			$disabledTask = ($taskChecked)? "" : "disabled";
			$disabledTask = ($task=='view')? "disabled" : $disabledTask;
			?>			
				<input <?=$disabledTask?> type="checkbox" name="modulelistTask[<?=$modid?>][]" id="mod<?=$modid?><?=$taskCode?>" <?=$taskChecked?> value="<?=$taskCode?>"><?=$taskName?>
		<?
			$previousModId=$currentModId;
			$count=1;
		}
		?>		
				</td></tr>	
				 
				<tr><td colspan="2">&nbsp;</td></tr>	
				
			<?if((strtolower($task)=='create') || (strtolower($task)=='edit') ){ ?>				
				<tr><td colspan="2"><input type="submit" class="flat_button" name="Save" value="Save"></td></tr>				
			<?}else{?>					
				<tr><td colspan="2"><?=$mform->inputBox($task,'button','cancel','Cancel','flat_button','cancel','','','','24');?></td></tr>
			<?}?>	
			</table>
			<div class="message"><p><? if(!empty($errmsg)){ foreach($errmsg as $errValue){ echo $errValue; } }?></p></div>			
		</form>
		</td>
	</tr>	
</table>
<script>
	function toggleModule(id,checked){
		check = document.getElementsByName('modulelistTask['+id+'][]');
		if(checked){
			for ( i=0; i < check.length; i++ ){
				check[i].disabled = false;
				check[i].checked = false;
			}
		}else{
			for ( i=0; i < check.length; i++ ){
				check[i].disabled = true;
				check[i].checked = false;
			}
		}
	}
	
	
</script>


