<?php
function getmainparentpage($tablename,$pageid){

$psql = "SELECT * FROM $tablename WHERE id=$pageid";
$pageconn=new connection;
$pageconn->qselectDb($psql);
$pageconn->fetchRs();
$currentlevel = $pageconn->rs['mlevel'];
$currparent	= $pageconn->rs['parentid'];
$currid = $pageid;
$templevel = $currentlevel;
	while($templevel>1 ){
		$psql2 = "SELECT * FROM $tablename WHERE id=$currparent";
		$pageconn2=new connection;
		$pageconn2->qselectDb($psql2);
		$pageconn2->fetchRs();
		$currid = $currparent;
		$currparent	= $pageconn2->rs['parentid'];
		$currentlevel = $currentlevel-1;
		
		//$currparent	= $pageconn2->rs['parentid'];
		
	}
$pagehead=$currid;

return $pagehead;
}





function getpageinfo($tablename,$field,$fieldcontent){
$psql = "SELECT * FROM $tablename WHERE $field='$fieldcontent'";
$pageconn=new connection;
$pageconn->qselectDb($psql);
$pageconn->fetchRs();
$pageinfo=$pageconn->rs;

return $pageinfo;
}

function getpagetitle($pageid){
$psql = "SELECT * FROM pages WHERE id='$pageid'";
$pageconn1=new connection;
$pageconn1->qselectDb($psql);
$pageconn1->fetchRs();
$thispage				=array();
$thispage['title']		=$pageconn1->rs['title'];
$thispage['content']	=$pageconn1->rs['content'];
$thispage['permalink']	=$pageconn1->rs['permalink'];
$thispage['publish_date']	=$pageconn1->rs['publish_date'];
$thispage['varied_author']	=$pageconn1->rs['varied_author'];
return $thispage;
}


function getparentinfo($tablename,$pageid){

$psql = "SELECT * FROM $tablename WHERE id=$pageid";
$pageconn=new connection;
$pageconn->qselectDb($psql);
$pageconn->fetchRs();
$parent_info=array();
$parent_info['level'] = $pageconn->rs['mlevel'];
$parent_info['id'] = $pageconn->rs['parentid'];

return $parent_info;
}


function get_tree($tablename){

$psql = "SELECT * FROM $tablename order by mlevel desc";
$pageconn=new connection();
$tconn=new connection();
$tree_array=array();
$pageconn->qselectDb($psql);
$level_limit=0;
	if($pageconn->fetchRs()){
		$level_limit=$pageconn->rs['mlevel'];
	}
for($tmpct=1;$tmpct<=$level_limit;$tmpct++){
	echo $tsql="SELECT * FROM $tablename WHERE mlevel='$tmpct'";
	$tconn->qselectDb($tsql);
	$tparent=$tconn->rs['parentid'];
	$tid=$tconn->rs['id'];
	$tlevel=$tconn->rs['mlevel'];
	$ttitle=$tconn->rs['title'];
	$tree_array[$tid]['title']=$ttitle;
	$tree_array[$tid]['parentid']=$tparent;
	$tree_array[$tid]['level']=$tlevel;
	
}




return $tree_array;
}


function getchildren($tablename,$parentid){

$psql = "SELECT * FROM $tablename WHERE parentid=$parentid";
$pageconn=new connection;
$pageconn->qselectDb($psql);
$childids="";
while($pageconn->fetchRs()){
$tmpid=$pageconn->rs['id'];
	if($childids!=""){
		$childids.=",".$tmpid;
	}else{
		$childids.=$tmpid;
	}
}
return $childids;
}



function get_titles($tablename,$id_range){
$title_array=array();
$psql = "SELECT * FROM $tablename WHERE id in($id_range)";
$pageconn=new connection;
$pageconn->qselectDb($psql);
while($pageconn->fetchRs()){
	$tmpid=$pageconn->rs['id'];
	$tmptitle=$pageconn->rs['title'];
	$tmpperma=$pageconn->rs['permalink'];
	$tmpcontent=$pageconn->rs['content'];
	//$tmplvl=$pageconn->rs['mlevel'];

	//$tmpcdate=$pageconn->rs['createdate'];
	//$title_array[$tmpid]['mlevel']=$tmplvl;
	$title_array[$tmpid]['title']=$tmptitle;
	$title_array[$tmpid]['permalink']=$tmpperma;
	$title_array[$tmpid]['id']=$tmpid;
	$title_array[$tmpid]['content']=$tmpcontent;
	//$title_array[$tmpid]['createdate']=$tmpcdate;
}
return $title_array;
}


function display_groupselect($grouptable,$grouparr){
$groupselector="";
$gsql = "SELECT * FROM $grouptable";
$groupconn=new connection;
$groupconn->qselectDb($gsql);
$groupselector.="<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
$groupselector.="<tr>";
$groupselector.="<td class=\"caption\" valign=\"top\"><span class=\"req\">*</span> Visibility (Please select a viewing group) :&nbsp;&nbsp; </td>";
$groupselector.="<td><select multiple=\"multiple\" name=\"groupsel[]\" id=\"groupsel\" style=\"border:1px solid #c0c0c0; width:200px\">";



while($groupconn->fetchRs()){
	$groupid=$groupconn->rs['id'];
	$groupname= $groupconn->rs['group'];
	if($groupname!='Admin'){
		if(!empty($grouparr)){
			if(in_array($groupid,$grouparr)){
				$groupselector.="<option selected value=\"$groupid\">".$groupconn->rs['group']."</option>";
			}else{
				$groupselector.="<option value=\"$groupid\">".$groupconn->rs['group']."</option>";}
		}else{
			$groupselector.="<option value=\"$groupid\">".$groupconn->rs['group']."</option>";
		}
	}
}
$groupselector.="</select><input type=\"hidden\" name=\"grouplist\" id=\"grouplist\" value=\"\"/></td>";
$groupselector.="</tr>";
$groupselector.="</table>";

return $groupselector;
}


function createPermaLink($string)
{
$string = preg_replace("/(:|;|-|\"|\/|\(|\)|\')/", "-", strtolower($string));
$string = preg_replace("/(\s)/", "-", strtolower($string));
$string = str_replace("%","-",$string);
$string = str_replace("&","-",$string);
return $string;
}


function chkChild($id){
	$contemp=new connection();
	$contemp->qselectDb("SELECT * FROM pages WHERE parentid='$id'");
	if($contemp->fetchRs()){
		return true;
	}else{
		return false;
	}
}

function chkChildviaTitle($title){
	$contemp=new connection();
	$contemp->qselectDb("SELECT * FROM pages WHERE title='$title'");
	
	if($contemp->fetchRs()){
		$tid = $contemp->rs['id'];
		return chkChild($tid);
	}else{
	//echo "SELECT * FROM pages WHERE title='$title'";
	}
	
}

function doesexists($pagetitle){
		$contemp=new connection();
		$page_query	="SELECT * FROM pages WHERE title LIKE '$pagetitle'";
		$contemp->qselectDb($page_query);
		if($contemp->fetchRs()){
			return true;
		}else{
			return false;
		}
	}

function predefexists($pagetitle){
		$contemp=new connection();
		$page_query	="SELECT * FROM reference WHERE name LIKE '$pagetitle' AND ref_name='predefpage' AND remarks1='enabled'";
		$contemp->qselectDb($page_query);
		if($contemp->fetchRs()){
			return true;
		}else{
			return false;
		}
}
function del_predefpage($predefname){
		$pdpage_id = "";
		$contemp=new connection();
		$page_query	="SELECT * FROM reference WHERE name LIKE '$predefname' AND ref_name='predefpage' AND remarks1='enabled'";
		$contemp->qselectDb($page_query);
		if($contemp->fetchRs()){
			$pdpage_id = $contemp->rs['ref_id'];
		}
		if($pdpage_id!=""){
			$predef_record 		= new record('pages');
			$predef_record->delete_record("id='$pdpage_id'");
		}
}
function pgright_pane(){//this function will be used to determine if the page would have an optional right pane
	$rightpane="";
	$rightpane.="<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">";
	$rightpane.="<tr>";
	$rightpane.="<td class=\"caption\" valign=\"top\"><span class=\"req\">*</span> Toggle Right Pane Visibility :&nbsp;&nbsp; </td>";
	$rightpane.="<td><input type=\"checkbox\" name=\"rightpane_chk\" id=\"rightpane_chk\" style=\"border:1px solid #c0c0c0; width:200px\">";
	$rightpane.="</tr>";
	$rightpane.="</table>";
	return $rightpane;
}	

function chkPageAccess($pageid,$accessid){
$gsql = "SELECT * FROM reg_groups rg WHERE rg.group='Admin'";
$gconn=new connection;
$gconn->qselectDb($gsql);
$gconn->fetchRs();
$adminid=$gconn->rs['id'];
if($accessid==$adminid){
return true;
}
//------------------------
$gsql = "SELECT * FROM reg_groups rg WHERE rg.group='Public'";
$gconn=new connection;
$gconn->qselectDb($gsql);
$gconn->fetchRs();
$pubid=$gconn->rs['id'];
//----------------------
$gsql = "SELECT * FROM reg_groups WHERE id='$accessid'";
$gconn=new connection;
$gconn->qselectDb($gsql);
$gconn->fetchRs();
$access=$gconn->rs['group'];
//-----------------------
$acc_array=array();

if($access!='Public'){
	$acc_array[0]=$accessid;
	$acc_array[1]=$pubid;
}else{
	$acc_array[0]=$accessid;
}

$existflag=0;
$psql = "SELECT * FROM pages WHERE id='$pageid'";
$pageconn=new connection;
$pageconn->qselectDb($psql);
	if($pageconn->fetchRs()){
		$this_access=$pageconn->rs['groups'];
		$accesses=explode(",",$this_access);	
	}
	foreach($acc_array as $aa =>$acc){
		if(in_array($acc,$accesses)){
			$existflag=1;
		}
	}
if($existflag==1){
	return true;
}else{
	return false;
}
}

function chkPageAccess2($pageid,$accessid){
$access_arr=explode(",",$accesid);

}


function chkContent($pageid){
$content="";
$psql = "SELECT * FROM pages WHERE id='$pageid'";
$pageconn=new connection;
$pageconn->qselectDb($psql);
	if($pageconn->fetchRs()){
	$content=$pageconn->rs['content'];
	}
	return $content;
}



//

?>