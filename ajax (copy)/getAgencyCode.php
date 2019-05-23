<?
ob_start();

include_once '../model/dbconfig.php';
include_once '../model/class/connectUpdate.inc';
include_once '../model/class/recordUpdate.inc';

$val 	= $_POST['agencyCode']; //id
$destinationCode = "";
$response = "";
$optionList = "";
$recon 	= new recordUpdate();
 
$destinationCode = $recon->retrieveCustomQuery("SELECT agencyName FROM agency WHERE agencyCode='$val'");
$destinationValue = explode("|",$destinationCode[0]);
$response = $destinationValue[0]."|";

$signatoryList = $recon->retrieveCustomQuery("SELECT agencySignatoryName FROM agency_signatories WHERE agencyCode='$val'");
foreach($signatoryList as $index => $value){
	$optionList .= "<option value=\"$value\">";
}
$dataArray			= array('signatories'=>$optionList);
echo $response.json_encode($dataArray);
ob_end_flush();
?>