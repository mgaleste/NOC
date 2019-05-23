<?
ob_start();

include_once '../model/dbconfig.php';
include_once '../model/class/connectUpdate.inc';
include_once '../model/class/recordUpdate.inc';

$val 	= $_POST['destinationName']; //id
$destinationCode = "";
$recon 	= new recordUpdate();
 
$destinationCode = $recon->retrieveCustomQuery("SELECT destinationCode FROM destination WHERE destinationName='$val'");
$destinationValue = explode("|",$destinationCode[0]);
echo $destinationValue[0];

ob_end_flush();
?>