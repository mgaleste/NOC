<?
ob_start();

include_once '../model/dbconfig.php';
include_once '../model/connection.php';
include_once '../model/functions.php';
include_once '../model/advanced_functions.php';
include_once '../model/connectUpdate.inc';
include_once '../model/recordUpdate.inc';

$val 	= $_POST['v1']; //id
$val2 	= $_POST['v2']; //category
$val3 	= $_POST['v3']; //parent
$val4 	= $_POST['v4']; //type
$recon 	= new recordUpdate();
 
$pageArr['id']  		= $val2;
$pageArr['category']  	= $val;
$pageArr['type']  		= $val4;
$pageArr['parent']  	= $val3;
$pageArr['permalink']   = str_replace(" ","-",$val);
$recon->insertRecord($pageArr,"category");
//exit;

ob_end_flush();
?>


	<select  class="iselect" id="catid3" >
		<option value="0">- Select One -</option>
		<? display_child('0','0',$val4); ?>
	</select>


