<?
include_once("fpdf.php");
class PDFmulticellTable extends FPDF
{
	var $widths;
	var $aligns;

	function SetWidths($w)
	{
		//Set the array of column widths
		$this->widths=$w;
	}

	function SetAligns($a)
	{
		//Set the array of column alignments
		$this->aligns=$a;
	}

function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}

	function NbLines($w,$txt)
	{
		//Computes the number of lines a MultiCell of width w will take
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
		    $w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if($nb>0 and $s[$nb-1]=="\n")
		    $nb--;
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb)
		{
		    $c=$s[$i];
		    if($c=="\n")
		    {
		        $i++;
		        $sep=-1;
		        $j=$i;
		        $l=0;
		        $nl++;
		        continue;
		    }
		    if($c==' ')
		        $sep=$i;
		    $l+=$cw[$c];
		    if($l>$wmax)
		    {
		        if($sep==-1)
		        {
		            if($i==$j)
		                $i++;
		        }
		        else
		            $i=$sep+1;
		        $sep=-1;
		        $j=$i;
		        $l=0;
		        $nl++;
		    }
		    else
		        $i++;
		}
		return $nl;
	}	
	function Header(){
			$this->SetMargins(5,5,5);
			$this->SetFont('Arial','',6);
			$this->Cell(80,0,$this->company,0,0,'L',false);	
			$this->Cell(80,0,"Printed By: ".$this->username,0,0,'L',false);	
			$this->Cell(60,0,"Printed On: ".$this->datetime,0,0,'R',false);	
			$this->Cell(60,0,"Page ".$this->PageNo().' of {nb}',0,0,'R',false);	
			$this->Ln(2);			
			if($this->PageNo()==1){
			$this->SetFont('Arial','B',12);		
			$this->Cell(0,10,strtoupper(str_replace("_"," ",$this->type)),0,2,'C',0);//page title	
			}	
			$this->Ln(2);				
					
			$this->SetFillColor(101,100,99);
			$this->SetTextColor(255, 255, 255);
			$this->SetDrawColor(255, 255, 255);
			$this->SetLineWidth(0.3);	
			$this->SetFont('Arial','B',7);
			//Calculate the height of the row
			foreach($this->header1 as $colstring => $colval){
				$this->Cell($colstring,7,strtoupper($colval),'R',0,'L',true);	
			}	
			$this->Ln();	
			$caption = $this->header2;
			$nb=0;	
			$ci=(count($this->header1));
			foreach($caption as $colstring1 => $colval1){
				$nb=max($nb,$this->NbLines($this->widths[$ci],$colval1));
				$h=6*$nb;
				$ci++;
			}			
			// Colors, line, width and bold font
			$this->SetDrawColor(255, 255, 255);
			$this->SetFillColor(101,100,99);			
			$this->SetTextColor(255, 255, 255);
			$this->SetFont('Arial','B',7);
			$this->SetLineWidth(0.3);
			$ci=(count($this->header1));
			foreach($caption as $colstring1 => $colval1){
				$w=$this->widths[$ci];
				//Save the current position
				$x=$this->GetX();
				$y=$this->GetY();
				//Draw the border
				
				$this->Rect($x,$y,$w,$h,'FD');
				//Print the text
				$this->MultiCell($w,7,strtoupper($colval1),0,'C',true);
				//Put the position to the right of the cell
				$this->SetXY($x+$w,$y);
				$ci++;
			}	
			$this->Ln($h);	
			$this->SetDrawColor(255, 255, 255);
			$this->SetFillColor(101,100,99);			
			$this->SetTextColor(255, 255, 255);
			$this->SetFont('Arial','B',7);
			$this->SetLineWidth(0.3);
			$head3count = count($this->header3);
			if($head3count > 0){				
				foreach($this->header3 as $colstring3 => $colval3){
					$this->Cell($colstring3,7,strtoupper($colval3),'T',0,'L',true);	
				}
			}
			$rownum = (($head3count > 0 )?10 :15);
			if($this->PageNo()==1){	$this->Ln($h-($rownum));}else{$this->Ln($h);}	
		}
	function Row($data)
	{
		//Calculate the height of the row
		$nb=0;
		$nb2=0;
		$nb3=0;	
		$n = 0;		
		$h=0;		
		foreach($data as $aIndex=>$content){
			$nb=max($nb,$this->NbLines($this->widths[$n],$content));
			$h=5*$nb;
				if(($n==(count($this->header1)-1)) || ($n== ((count($this->header1)) +  (count($this->header2)) - 1))){
					$this->Ln(5);
				}									
			$n++;			
		}
		//Issue a page break first if needed
		$this->CheckPageBreak($h2+(14));
		//Draw the cells of the row
		$this->SetFillColor(234, 234, 234);
		$this->SetDrawColor(107,105,103);
		$this->SetTextColor(0);
		$this->SetFont('Arial','',6);		
		$fill = $this->fill;
		$fill2 = $this->fill2;
			$i = 0;
			foreach($data as $aIndex=>$content){	
			#formatting content
				if($content=='0000-00-00 00:00:00'){$content="";}
				if(preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})/',$content)){
					$content = date("M d, Y h:i:s a",strtotime($content));
				}else if(preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})/',$content)){
					$content = date("M d, Y",strtotime($content));
				}else if( preg_match('/^([0-9]+)([\.]{1})([0-9]+)/',$content)){
					$content = number_format($content,2);
				}else if( preg_match('/^([0-9]{4})/',$content) || preg_match('/^([0-9]{2})/',$content) || preg_match('/^([0-9]{1})/',$content) ){}
						
				$w=$this->widths[$i];
				$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';				
				//$a = 'L';			
					//Save the current position
				$x=$this->GetX();
				$y=$this->GetY();										
				//Draw the border
				$h2 = (( ($i>(count($this->header1)-1)) && $i<= ((count($this->header1)) + (count($this->header2))-1))? $h : 5);
				$this->Rect($x,$y,$w,$h2,$fill2);
				//Print the text
					$this->MultiCell($w,5,$content,0,$a,$fill);				
				//Put the position to the right of the cell
				$this->SetXY($x+$w,$y);																	
					if(($i==(count($this->header1)-1))||($i== ((count($this->header1)) + (count($this->header2))-1))){
							$this->Ln($h2);
					}							
				$i++;
			}					
			$rownum = (( (count($this->header3))>0 )?2 :3);
		//Go to the next line
		$this->Ln($h2- ($h2*$rownum));
	}
}
?>
