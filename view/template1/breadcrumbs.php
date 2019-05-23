<?
$recon	 				= new recordUpdate();

$task		 	=  	isset($_GET['task']) ? $_GET['task'] : "";		
$module 		=  	isset($_GET['mod']) ? $_GET['mod'] : "";		
$type	 		=  	isset($_GET['type']) ? $_GET['type'] : "";	
		
$codition = (!empty($type))? " WHERE type= '".$type."'" : " WHERE moduleName= '.$module.'";
$queryCaption = "SELECT moduleCaption FROM modules $codition";
$retrieveCaption = $recon->retrieveCustomQuery($queryCaption);
		
if(!empty($module) && !isset($_GET['mode'])){
?>
<table cellpadding="0" cellspacing="0" width="98%" >	
	<tr>
		<td style="padding:0px 10px">	  		 
			<a href="index.php">Home</a> >
		<?
			if(!empty($module) && ($module==$type)){
				$typeCaption = str_ireplace("_"," ",$type);
				echo '<a href="index.php?mod='.$module.'&type='.$type.'">'.ucwords($typeCaption).'</a> >'."&nbsp;";
			}else{
				$typeCaption = str_ireplace("_"," ",$module);
					echo '<a href="index.php?mod='.$module.'">'.ucwords($module).'</a> >'."&nbsp;";
			}
			if(!empty($type) && ($module!=$type)){
				$typeCaption = str_ireplace("_"," ",$type);
				echo '<a href="index.php?mod='.$module.'&type='.$type.'">'.ucwords($typeCaption).'</a> >'."&nbsp;";
			}
			if(!empty($task)){
				
				echo '<a href="index.php?mod='.$module.'&type='.$type.'">'.ucfirst($task).' '.ucwords($typeCaption).'</a> >'."&nbsp;";
			}
		?>	
		</td>
	</tr>
</table>
<?}?>
