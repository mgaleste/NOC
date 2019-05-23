<?php 
	ob_start();
	ini_set("memory_limit","128M");
	
	include_once('../../site.php');
	include_once('../../model/class/csfPdf.inc');
	
	$agency		= isset($_GET['sid']) ? $_GET['sid'] : "";
	$datetimefile           =   date("Ymd_His");
	
	$recon		= new recordUpdate();
	
	$mainDetails	=	$recon->retrieveCustomQuery("SELECT * FROM agency WHERE agencyCode='$agency'");
	$signatories	=	$recon->retrieveCustomQuery("SELECT agencySignatoryName, designation, agencySignatoryCode FROM agency_signatories WHERE agencyCode='$agency' ORDER BY agencySignatoryName");
	$accountExecutives	=	$recon->retrieveCustomQuery("SELECT accountExecutiveCode FROM signatories_account_executives JOIN agency_signatories USING(agencySignatoryCode) WHERE agencyCode='$agency'");
	
	$csfpdf		= new csfPdf('P','mm','A4'); 
	$csfpdf->SetTitle("Client Specification File :: ".$agency,true);
	
	$allSignatories = array();
	
	$csfpdf->AliasNbPages();
    $csfpdf->AddPage();
    $csfpdf->SetFont('Arial','',7);
	
	$csfpdf->mainDetail($mainDetails[0]);
	$allSignatories = $csfpdf->signatoryList($signatories);
	
	$csfpdf->AddPage();
    $csfpdf->SetFont('Arial','',7);
    
	if(!empty($allSignatories)){
		$allSignatories = implode(",",$allSignatories);
		$exams	=	$recon->retrieveCustomQuery("SELECT packageCode, packageDescription, destinationCode, m.grossAmount, m.netAmount FROM signatory_package_rate m JOIN exam_package USING(packageCode) WHERE signatoryCode IN($allSignatories) ORDER BY destinationCode");
    }else{
		$exams = array();
	}
	
    $csfpdf->agencyExams($exams);
	
	$csfpdf->AddPage();
    $csfpdf->SetFont('Arial','',7);
	
	$instructions	=	$recon->retrieveCustomQuery("SELECT instructionDate, instruction FROM  special_instructions WHERE agencyCode='$agency' ORDER BY instructionDate ASC");
	$csfpdf->agencyInstructions($instructions);
	
	$csfpdf->Cell(190,10,'',0,0,'L');
	$csfpdf->Ln();
	
	$csfpdf->SetFont('Arial','U',7);
	$csfpdf->SetTextColor(0, 0, 0);
	$csfpdf->Cell(190,5,'Client Relations Manager/s',0,0,'L');
	$csfpdf->Ln();
	$csfpdf->SetFont('Arial','',7);
	
	if(!empty($accountExecutives)){
		foreach($accountExecutives as $index => $data){
			$values = explode("|",$data);
			$csfpdf->Cell(190,5,$values[0],0,0,'L');
			$csfpdf->Ln();
		}
	}else{
		$csfpdf->Cell(190,5,'N/A',0,0,'L');
		$csfpdf->Ln();
	}
	
	$csfpdf->Cell(190,10,'',0,0,'L');
	$csfpdf->Ln();
	
	$csfpdf->Cell(190,2,'_____________________________',0,0,'L');
	$csfpdf->Ln();
	$csfpdf->Cell(190,5,'Approving Authority',0,0,'L');

	$csfpdf->Output("csf_".$datetimefile.".pdf","D");
	ob_end_flush(); 
?>				
