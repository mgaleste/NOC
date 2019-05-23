<?
session_start();
if(strtolower($_SERVER['REQUEST_METHOD']) != 'post'){
	exit;
}
$file = isset($_POST['file'])?$_POST['file']:"";
$fname = isset($_POST['fname'])?$_POST['fname']:"";
$lname = isset($_POST['lname'])?$_POST['lname']:"";
$date = date('Y-m-d');
$fname	= str_replace(" ","_",$fname);
$fname	= strtolower($fname);
$lname	= str_replace(" ","_",$lname);
$lname	= strtolower($lname);

$dir = "../applicants_directory/$date";
$filename = 'trial.png';

$_SESSION['details']['image']	= $file;
$_SESSION['details']['photo']	= $filename;

$fileExist = file_exists($dir.'/'.$filename);
$dirExist = file_exists($dir);
var_dump($dirExist);
if(!$dirExist){
mkdir("../applicants_directory/$date");
chmod("../applicants_directory/$date",0777);
}
if(!$fileExist){
$fileArr = explode(",",$file);
$unencoded = base64_decode($fileArr[1]);
$fp = fopen($dir.'/'.$filename, 'w');
fwrite($fp, $unencoded);
fclose($fp); 
echo "Photo Successfully Saved";
}else{
unlink($dir.'/'.$filename);
$fileArr = explode(",",$file);
$unencoded = base64_decode($fileArr[1]);
$fp = fopen($dir.'/'.$filename, 'w');
fwrite($fp, $unencoded);
fclose($fp);
}


?>
