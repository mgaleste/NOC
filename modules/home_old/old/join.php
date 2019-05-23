<?
ob_start(); 
include_once('./../../../site.php');
$recon 			=  new recordUpdate();

if($_POST){
$title		=	$_POST['title'];
$content	=	$_POST['contents']; 
$catid		=	(isset($_POST['catid'])) ? $_POST['catid'] : 1;
$type		=	$_POST['type'];

 

if($type=='testimonials'){
$arrayValues['author'] 		= $title;
}else{
$arrayValues['title'] 		= $title;
}
$arrayValues['content'] 	= $content;
$arrayValues['catid'] 		= $catid;
$arrayValues['type'] 		= $type; 
$arrayValues['status'] 		= "published"; 


$recon->insertRecord($arrayValues, "pages");  
 
 }
 
ob_flush();
?>