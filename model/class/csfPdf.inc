<?php	
	class csfPdf extends FPDF{
		function Header(){
			$this->SetFont('Arial','B',15);
			$this->Cell(190,10,'CLIENT SPECIFICATION FILE',0,0,'C',false);
			$this->Ln();
		}				
		
		function Footer(){
			$this->SetY(-15);
			$printDate = date("m/d/Y",strtotime('now'));
			$pageNo = $this->PageNo();
			$this->SetFont('Arial','B',7);
			$this->SetTextColor(0, 0, 0);
			$this->Cell(63,10,'CLIENT SPECIFICATION FILE',0,0,'L',false);
			$this->Cell(63,10,'Print Date : '.$printDate,0,0,'C',false);
			$this->Cell(63,10,'Page '.$pageNo,0,0,'R',false);
			$this->Ln();
		}
		
		function mainDetail($mainDetails){
			$data = explode("|",$mainDetails);
			
			//code and status line
			$this->SetFont('Arial','B',7);
			$this->Cell(25,5,'Client Code',0,0,'L',false);
			$this->SetFont('Arial','',7);
			$this->Cell(100,5,$data[0],0,0,'L',false);
			$this->SetFont('Arial','B',7);
			$this->Cell(25,5,'Status',0,0,'L',false);
			$this->SetFont('Arial','',7);
			$this->Cell(40,5,$data[20],0,0,'L',false);
			$this->Ln();
			
			//name line
			$this->SetFont('Arial','B',7);
			$this->Cell(25,5,'Name',0,0,'L',false);
			$this->SetFont('Arial','',7);
			$this->Cell(165,5,$data[1],0,0,'L',false);
			$this->Ln();
			
			//address line
			$this->Cell(25,5,'',0,0,'L');
			$this->Cell(165,5,$data[2],0,0,'L',false);
			$this->Ln();
			
			//tel no line
			$this->SetFont('Arial','B',7);
			$this->Cell(25,5,'Tel. Nos.',0,0,'L',false);
			$this->SetFont('Arial','',7);
			$this->Cell(100,5,$data[3],0,0,'L',false);
			$this->SetFont('Arial','B',7);
			$this->Cell(25,5,'Cellphone',0,0,'L',false);
			$this->SetFont('Arial','',7);
			$this->Cell(40,5,$data[8],0,0,'L',false);
			$this->Ln();
			
			//tel no and fax
			$this->Cell(25,5,'',0,0,'L',false);
			$this->Cell(100,5,$data[4],0,0,'L',false);
			$this->SetFont('Arial','B',7);
			$this->Cell(25,5,'Fax Nos.',0,0,'L',false);
			$this->SetFont('Arial','',7);
			$this->Cell(40,5,$data[6],0,0,'L',false);
			$this->Ln();
			
			//tel no and fax
			$this->Cell(25,5,'',0,0,'L',false);
			$this->Cell(100,5,$data[5],0,0,'L',false);
			$this->Cell(25,5,'',0,0,'L',false);
			$this->Cell(40,5,$data[6],0,0,'L',false);
			$this->Ln();
			
			//payment mode
			$this->SetFont('Arial','B',7);
			$this->Cell(25,5,'Payment Mode',0,0,'L',false);
			$this->SetFont('Arial','',7);
			$this->Cell(100,5,$data[11],0,0,'L',false);
			$this->SetFont('Arial','B',7);
			$this->Cell(25,5,'Medical Results',0,0,'L',false);
			$this->SetFont('Arial','',7);
			$this->Cell(40,5,$data[12],0,0,'L',false);
			$this->Ln();
			
			//terms
			$this->SetFont('Arial','B',7);
			$this->Cell(25,5,'Terms',0,0,'L',false);
			$this->SetFont('Arial','',7);
			$this->Cell(100,5,$data[14],0,0,'L',false);
			$this->Cell(25,5,'',0,0,'L',false);
			$this->Cell(40,5,'',0,0,'L',false);
			$this->Ln();
		}
		
		function signatoryList($signatories){
			$signatoryCode = array();
			$fill2 = true;
			
			$this->SetFont('Arial','B',10);
			$this->Cell(190,10,'SIGNATORIES',0,0,'C',false);
			$this->Ln();
			$this->SetFont('Arial','B',8);
			$this->Cell(95,10,'Signatory',0,0,'L',false);
			$this->Cell(95,10,'Designation',0,0,'L',false);
			$this->Ln();
			if(!empty($signatories)){
				foreach($signatories as $index => $data){
					$values = explode("|",$data);
					$this->SetFont('Arial','',7);
					$this->SetFillColor(234, 234, 234);
					$this->Cell(95,5,$values[0],0,0,'L',$fill2);
					$this->Cell(95,5,$values[1],0,0,'L',$fill2);
					$this->Ln();
					$signatoryCode[] = "'{$values[2]}'";
					$fill2 		= !$fill2;
				}
			}else{
				$this->SetFont('Arial','',7);
				$this->SetTextColor(255, 0, 0);
				$this->SetFillColor(234, 234, 234);
				$this->Cell(190,5,'No Signatories Available',0,0,'L',true);
				$this->Ln();
				$signatoryCode = false;
			}
			return $signatoryCode;
		}
		
		function agencyExams($exams){
			$fill2 = true;
			
			$this->SetFont('Arial','B',10);
			$this->Cell(190,10,'PACKAGES/INDIVIDUAL EXAMS AVAILED',0,0,'C',false);
			$this->Ln();
			$this->SetFont('Arial','B',8);
			$this->Cell(35,10,'Exam',0,0,'L',false);
			$this->Cell(55,10,'Exam Description',0,0,'L',false);
			$this->Cell(30,10,'Destination',0,0,'L',false);
			$this->Cell(35,10,'Amount',0,0,'L',false);
			$this->Cell(35,10,'Net',0,0,'L',false);
			$this->Ln();
			
			if(!empty($exams)){
				foreach($exams as $index => $data){
					$values = explode("|",$data);
					$this->SetFont('Arial','',7);
					$this->SetFillColor(234, 234, 234);
					$this->Cell(35,5,$values[0],0,0,'L',$fill2);
					$this->Cell(55,5,$values[1],0,0,'L',$fill2);
					$this->Cell(30,5,$values[2],0,0,'L',$fill2);
					$this->Cell(35,5,$values[3],0,0,'L',$fill2);
					$this->Cell(35,5,$values[4],0,0,'L',$fill2);
					$this->Ln();
					$fill2= !$fill2;
				}
			}else{
				$this->SetFont('Arial','',7);
				$this->SetTextColor(255, 0, 0);
				$this->SetFillColor(234, 234, 234);
				$this->Cell(190,5,'No Packages Available',0,0,'L',true);
				$this->Ln();
			}
		}
		
		function agencyInstructions($instructions){
			$fill2 = true;
			
			$this->SetFont('Arial','B',10);
			$this->Cell(190,10,'SPECIAL INSTRUCTIONS',0,0,'C',false);
			$this->Ln();
			$this->SetFont('Arial','B',8);
			$this->Cell(55,10,'Date',0,0,'L');
			$this->Cell(135,10,'Special Instructions',0,0,'L');
			$this->Ln();
			
			if(!empty($instructions)){
				foreach($instructions as $index => $data){
					$values = explode("|",$data);
					$this->SetFont('Arial','',7);
					$this->SetFillColor(234, 234, 234);
					$this->Cell(55,5,$values[0],0,0,'L',$fill2);
					$this->Cell(135,5,$values[1],0,0,'L',$fill2);
					$this->Ln();
					$fill2= !$fill2;
				}
			}else{
				$this->SetFont('Arial','',7);
				$this->SetTextColor(255, 0, 0);
				$this->SetFillColor(234, 234, 234);
				$this->Cell(190,5,'No Special Instructions Available',0,0,'L',true);
				$this->Ln();
			}
		}
			
	}

?>