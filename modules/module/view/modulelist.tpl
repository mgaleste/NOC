<table width="100%" class="form paddleft paddright" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td align="left" class="header"><a href="index.php?mod=<?=$mod?>&type=<?=$type?>"><?=strtoupper(rep_under($type))?></a></td>
		<td align="right">	
			<form method="POST">
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td colspan="4" align="right"><?= $mform->inputBox($task,'text','psearch_entry',"",'search input','psearch_entry','45');?></td>
						<td>&nbsp;<?= $mform->inputBox($task,'submit','doSearch',"Search",'roundbuttons button2','doSearch','5');?></td>
					</tr>
				</table>
			</form>
		</td>
	</tr>		 
	 
	 
	<tr>
		<td colspan="2">
			<form method="POST"  onsubmit="return ValidateForm(this, 'delAnn[]');">
			<div>
		 		<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<tr valign="middle">						
						<td class="verdana10"><a href="index.php?mod=<?=$mod?>&type=<?=$type?>&task=create" class="sub_link"><img src="<?=MODIMAGES;?>create.gif" border="0" alt=""> Create <?=ucfirst($type)?></a></td>
						<td class="verdana10"><input style="border:none" type="image" src="<?=MODIMAGES;?>delete.gif" name="submit_delete" width="16" height="16"><button name="submit_delete" class="delbut">Delete</button>[<input name="hidden_selected" class="verdana10 input_hidden" type="text" id="removeChecked" value="0">]<button name="submit_delete"  class="delbut"><?=ucfirst($type)?>(s)</button></td>
						<td  align="right" class="verdana10"><?= $module->showItems();?></td>
					</tr>
				</table>					 
			</div>			
			<table width="100%" cellpadding="0" cellspacing="0" class="sortable">					
				<thead>
					<tr valign="middle">
						<th width="5%" align="center" class="table_line1_left sorttable_nosort"><input onclick="checkAllFields(1);" id="checkAll" type="checkbox"></th>
						<th width="25%" class="left table_line1_left paddleft">Module Caption</th>
						<th width="40%" class="left table_line1_left paddleft">Module Name</th>
						<th width="20%" class="left table_line1_left paddleft">Module Type</th>
						<th width="10%" class="left table_line1_left_right paddleft sorttable_nosort">Order</th>
					</tr>					
				</thead>			
				<tfoot>
					<tr valign="middle">
						<td width="5%" align="center" class="table_line1_left">&nbsp;</td>
						<td width="25%" class="left table_line1_left paddleft">Module Caption</td>
						<td width="40%" class="left table_line1_left paddleft">Module Name</td>
						<td width="20%" class="left table_line1_left paddleft">Module Type</td>
						<td width="10%" class="left table_line1_left_right paddleft">Order</td>
					</tr>					
					<tr valign="middle">
						<td class="table_footer_left paddleft" align="left"  colspan="2"><?=$PAGINATION_INFO?></td>					
						<td class="table_footer_left_right" colspan="3" align="center"><span class="link"><?=$PAGINATION_LINKS?></span></td>
					</tr>		
				</tfoot>
				<tbody>			
			<?php
				$bgcolor = "";
				if($num_rows>0){	
					for($i=0;$i<$num_rows;$i++){					
						$ids 				= mysql_result($result, $i, "id");
						$modulename		 	= mysql_result($result, $i, "modulename");
						$modulecaption		= mysql_result($result, $i, "modulecaption");						 
						$menuorder			= mysql_result($result, $i, "menuorder");
						$moduletype			= mysql_result($result, $i, "type");
						$stat			 	= mysql_result($result, $i, "stat");						 
					
						$inner_pane			= "<div class=\"none\" style=\"padding-top:5px; display:none\" id=\"{$ids}\"><font face=\"verdana\" size=\"2\"><a href=\"index.php?mod=$mod&type=$type&task=edit&sid=$ids\" class=\"none\" title=\"Edit this Module\">Edit </a>| <a href=\"index.php?mod=$mod&type=$type&task=delete&sid=$ids\" onclick=\"return confirm('Are you sure you want to delete?');\" class=\"none\" title=\"Delete this Module\">Delete </a></font></div>";
						$bgcolor 			= ($bgcolor != "#FFFFFF")? "#FFFFFF" : "#EFEFEF";
						echo "<tr bgcolor=\"$bgcolor\" >";
							echo '	<td align="center" class="table_line2_left"><input class="ibox" value="'.mysql_result($result, $i, "id").'" name="delAnn[]" onclick="checkAllFields(2);" type="checkbox"></td>
									<td class="paddleft table_line2_left page_title" valign="top" align="left" onmouseover="show_div(\''.$ids.'\')" onmouseout="hide_div(\''.$ids.'\')"><a  style="text-decoration:none" href="index.php?mod='.$mod.'&type='.$type.'&task=edit&sid='.$ids.'">'.$modulecaption.'</a>'.$inner_pane.'</td>
									<td class="table_line2_left paddleft" valign="top" align="left">'.$modulename.'</td>
									<td class="table_line2_left paddleft" valign="top" align="left">'.$moduletype.'</td>';
							//echo '	<td align="left" valign="top"  class="table_line2_left_right paddleft category">'.$menuorder.'&nbsp;</td>';
							echo '	<td class="table_line2_left_right page_title paddleft " valign="middle" align="center"><input type="hidden" value="'.$ids.'" name="rowid" class="rowid'.$ids.'"><input type="text" value="'.$menuorder.'" name="porder" class="iputshort2 orderby'.$ids.'" style="text-align:right; padding-right:5px; border:1px solid #c0c0c0; width:25px; height:25px"></td>';
						echo '</tr>';
						
						
										
echo "<script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js\"></script>
<script type=\"text/javascript\">
$(document).ready(function() {

	function loading_show(){    
		$('#loading').html(\"<span style='color:red'>Loading...</span>\").fadeIn('fast');
    }
	
    function loading_hide(){
        $('#loading').fadeOut('fast');
    }
				
	$('.orderby".$ids."').change(function(){
		loading_show();
		var orderby = $('.orderby".$ids."').val();
		var rowid	= $('.rowid".$ids."').val();		 
		var rows = \"&rowid=\"+rowid;
		$.ajax({
                type: \"POST\",
				url: \"modules/module/save_order.php\",
                data: \"orderby=\"+orderby+rows,
                success: function(msg)
                {
                  $(\".successform\").ajaxComplete(function(event, request, settings)
                  {
                     loading_hide();
                     $(\".successform\").html(msg);
                   });
                }
        });
		
		
	});	
});
</script>";
						
					}
				}else{
					echo '<tr valign="middle"><td colspan="5" class="table_line2_left table_line2_left_right" align="center"><p class="errmsg">NO RECORDS FOUND</p></td></tr>';
				}
				?>		
				</tbody>
			</table>
			</form>
		</td>
	</tr>	 
</table>


 	