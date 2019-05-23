<?
ob_start();

include_once '../model/dbconfig.php';
include_once '../model/class/connectUpdate.inc';
include_once '../model/class/recordUpdate.inc';

$val 	= $_POST['signatoryName']; //id
$destinationCode = "";
$response = "";
$optionList = "";
$recon 	= new recordUpdate();
 
$destinationCode = $recon->retrieveCustomQuery("SELECT agencySignatoryCode FROM agency_signatories WHERE agencySignatoryName='$val'");
$destinationValue = explode("|",$destinationCode[0]);
$response = $destinationValue[0]."|";

$signatoryList = $recon->retrieveCustomQuery("SELECT destinationName FROM signatories_destination JOIN destination USING(destinationCode) WHERE agencySignatoryCode='".$destinationValue[0]."'");

foreach($signatoryList as $index => $value){
	$optionList .= "<option value=\"$value\">";
}
$dataArray			= array('destinations'=>$optionList);
echo $response.json_encode($dataArray);

ob_end_flush();
?>