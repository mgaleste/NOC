<?
 		$task		 	=  	isset($_GET['task']) ? $_GET['task'] : "";		
		$module 		=  	isset($_GET['mod']) ? $_GET['mod'] : "";		
		$type	 		=  	isset($_GET['type']) ? $_GET['type'] : "";	
		
 if((!empty($module) && $module=='profile')){
?>

<table cellpadding="0" cellspacing="0" width="200px" >	
	<tr>
		<td>	  		 
			<ul class="sidebar"> 
			<? if($type=="gallery" || $type=="album" || $type=="photo"){?>
				<li><a href="index.php?mod=<?=$module?>&type=album&task=create">Create New Photo Album</a></li>	 
				<li><a href="index.php?mod=<?=$module?>&type=gallery">List of Photo Album</a></li>	 
			
			<?}else if($type=="video"){?>
				<li><a href="index.php?mod=<?=$module?>&type=<?=$type?>&task=create">Embed New <?=ucwords(str_replace("_"," ",$type))?></a></li>	 
				<li><a href="index.php?mod=<?=$module?>&type=<?=$type?>">List of <?=ucwords(str_replace("_"," ",$type))?></a></li>	 
			<?}else if($type=="info" || $type=="pass"){?>
				<li><a href="index.php?mod=profile&type=info&task=edit">Update Profile</a></li>	 
				<li><a href="index.php?mod=profile&type=pass">Change Password</a></li>	 
			<?}else{?>
				<li><a href="index.php?mod=<?=$module?>&type=<?=$type?>&task=create">Create New <?=ucwords(str_replace("_"," ",$type))?></a></li>	 
				<li><a href="index.php?mod=<?=$module?>&type=<?=$type?>">List of <?=ucwords(str_replace("_"," ",$type))?></a></li>	 
			<?}?>	
			</ul>			 
		</td>
	</tr>
</table>
<?}?>