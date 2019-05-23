<table width="100%" class="form" cellpadding="10" cellspacing="10" border="0">
<tr>
    <td colspan="2">
        <?
            $menuSelected = array();
            $menuSelected['site'] = ($cat=='site')? 'flat_button_selected' : 'flat_button';
            $menuSelected['payment'] = ($cat=='payment')? 'flat_button_selected' : 'flat_button';
            $menuSelected['ticket'] = ($cat=='tickets')? 'flat_button_selected' : 'flat_button';
            $menuSelected['logs'] = ($cat=='logs')? 'flat_button_selected' : 'flat_button';

            echo $mform->inputBox('edit','button','sitestatushistory','Site Status History',$menuSelected['site'],'sitestatushistory','',' onClick="redirectButton(\'index.php?mod='.$mod.'&type='.$type.'&cat=site\');" ','','24');
            echo $mform->inputBox('edit','button','paymentstatushistory','Payment Status History',$menuSelected['payment'],'paymentstatushistory','',' onClick="redirectButton(\'index.php?mod='.$mod.'&type='.$type.'&cat=payment\');" ','','24');
            echo $mform->inputBox('edit','button','Ticketslist','Tickets History',$menuSelected['ticket'],'ticketslist','',' onClick="redirectButton(\'index.php?mod='.$mod.'&type='.$type.'&cat=tickets\');" ','','24');
            echo '&nbsp;&nbsp;'.$mform->inputBox('edit','button','logs','Admin Logs',$menuSelected['logs'],'adminlogs','',' onClick="redirectButton(\'index.php?mod='.$mod.'&type='.$type.'&cat=logs\');" ','','24');
        ?>
    </td>
</tr>
<script type="text/javascript">
    function redirectButton(url){
        location.href=url;
    }

</script>
<?
if(!empty($cat)){ ?>
<tr>
		<td align="left" class="header"></td>
		<td align="right">	
			<form method="POST">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="4" align="right"><?= $mform->inputBox($task,'text','psearch_entry',$psearch,'search input','psearch_entry','15');?></td>
					<td>&nbsp;<?= $mform->inputBox($task,'submit','doSearch',"Search",'roundbuttons button2','doSearch','5');?></td>
				</tr>
			</table>
			</form>
		</td>
	</tr>		 
<?
}
?>
	 
	
