<?php 
	ob_start();
	ini_set("memory_limit","128M");
	
	include_once('../site.php');
	
	$agency		= isset($_GET['sid']) ? $_GET['sid'] : "";
	
	$mpdf		= new mPDF(); 

$mpdf->SetHTMLHeader('<table width="100%" cellpadding="0" cellspacing="0" border="0" style="font-family:verdana; font-size:6pt"> 
		<tr>
		<th><b>CLIENT SPECIFICATION FILE</b></th>
		</tr></table>','0','true'); 

$mpdf->WriteHTML(utf8_encode('<html><body>'));

	$html 		= '';
	$html		.= '<table>';
		$html		.= '<tbody>';
					$html.= '<tr><td>Client Code</td><td>:</td><td>'.$agencyCode.'</td><td>Status</td><td>:</td><td>'.$systemStatus.'</td></tr>';
		$html.= '<tr><td>Name</td><td>:</td><td colsapn="4">'.$agencyName.'</td></tr>';
		$html.= '<tr><td colspan="2"></td><td colspan="4">'.$address.'</td></tr>';
		$html.= '<tr><td>Tel. Nos.</td><td>:</td><td>'.$phone1.'</td><td>Cellphone No.</td><td>:</td><td>'.$cellphone.'</td>	</tr>';
		$html.= '<tr><td></td><td></td><td>'.$phone2.'</td><td>Fax Nos.</td><td>:</td><td>'.$fax1.'</td></tr>';
		$html.= '<tr><td></td><td></td><td>'.$phone3.'</td><td></td><td></td><td>'.$fax2.'</td>	</tr>';
		$html.= '<tr><td>Payment Mode</td><td>:</td><td>'.$paymentMode.'</td><td>Medical Results</td><td>:</td><td>'.$medicalResult.'</td></tr>';
		$html.= '<tr><td>Terms</td><td>:</td><td>'.$terms.'</td><td></td><td></td><td></td></tr>';								
		$html		.= '</tbody>';
	$html		.= '</table>';		
	
$mpdf->WriteHTML(utf8_encode('</body></html>'));	
	$mpdf->WriteHTML($html);
	$mpdf->Output();
	ob_end_flush(); 
?>				
