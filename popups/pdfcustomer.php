<?php 
	ob_start();	 
	session_start();
	include_once("../site.php"); 
	$mconn				=	new connection();		
	$mpdf 				= 	new mPDF('c');		 
	//$userclass 			= 	new maintenance('officers');
	$user				= 	isset($_GET['username'])? $_GET['username'] : $_SESSION['username'];
	$username			=	$_GET['user'];
	$pass 				= 	$_GET['pass'];
	$datetime			= 	date("Y-m-d H:i:s");
	$datetimefile		= 	date("Ymd_H_i_s");	
	$cmp 				= 	isset($_GET['cmp'])?$_GET['cmp']: $_SESSION['companycode'];
 	$rec_query = "SELECT * FROM customer WHERE username='$username'";
	$mconn->qselectDb($rec_query);
	if($mconn->fetchRs()){				
				$uname 				= $mconn->rs['username'];		
				$firstname 			= $mconn->rs['firstName'];				
				$mname 				= $mconn->rs['middleName'];
				$lastname 			= $mconn->rs['lastName'];		
				$registered_date 	= $mconn->rs['enteredDate'];
			}	
		
	$class1 =  'style="color:#525252;font-family:verdana;font-size:12pt"';
	$class2 =  'style="color:#525252;font-family:verdana;font-size:11pt"';
	//$companyName = $userclass->GetValue("company","company","companycode='$cmp'");	
	function Urlget(){
		$pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
		if ($_SERVER["SERVER_PORT"] != "80")
		{
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		}
		else
		{
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}	
		$site_part = explode("popups",$pageURL);
		$true_site = $site_part[0];
		return $true_site;
	}
		
$mpdf->WriteHTML(utf8_encode('<html><body>'));
$mpdf->SetHTMLHeader(showHeader("MTPB",$user,$datetime),'0','true');
$mpdf->WriteHTML(utf8_encode('
				<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
					<tr>
						<td width="10%">&nbsp;</td>
						<td align="center" width="80%">
							<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center">
								<tr>
									<td colspan="4" height="100px">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="4" '.$class1.'>
										<h3><b><u>ACCESS DETAILS</u></b></h3>
									</td>
								</tr>
								<tr>
									<td colspan="4" height="30px">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="2" width="30%" '.$class2.'><b>Username : </b></td>
									<td colspan="2" width="70%" '.$class2.'>'.$username.'</td>
								</tr>
								<tr>
									<td colspan="2" '.$class2.'><b>Password : </b></td>
									<td colspan="2" '.$class2.'>'.$pass.'</td>
								</tr>
								<tr><td colspan="4" height="50px">&nbsp;</td></tr>
								<tr>
									<td colspan="4" '.$class1.'>
										<h3><b><u>PERSONAL DETAILS</u></b></h3>
									</td>
								</tr>
								<tr><td colspan="4" height="30px">&nbsp;</td></tr>
								<tr>
									<td colspan="2" '.$class2.'><b>Last Name : </b></td>
									<td colspan="2" '.$class2.'>'.$lastname.'</td>
								</tr>
								<tr>
									<td colspan="2" '.$class2.'><b>First Name : </b></td>
									<td colspan="2" '.$class2.'>'.$firstname.'</td>
								</tr>
								<tr>
									<td colspan="2" '.$class2.'><b>Middle Name : </b></td>
									<td colspan="2" '.$class2.'>'.$mname.'</td>
								</tr>
								
								<tr>
									<td colspan="2" '.$class2.'><b>Registered Date :</b> </td>
									<td colspan="2" '.$class2.'>'.date("M d, Y h:i:s a",strtotime($registered_date)).'</td>
								</tr>
								<tr><td colspan="4" height="30px">&nbsp;</td></tr>
							</table>
						</td>
						<td width="10%">&nbsp;</td>
					</tr>
				</table></body></html>'));
 
$mpdf->Output('../user_pdf/'.$username.'.pdf','F');
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.basename('../user_pdf/'.$username.'.pdf'));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize('../user_pdf/'.$username.'.pdf'));
			ob_clean();
			flush();
			readfile('../user_pdf/'.$username.'.pdf');
			exit;
		
function showHeader($companyName,$username,$datetime){
$header = '';
$header .= '
	<table width="100%" cellpadding="0" cellspacing="0" border="0" style="font-family:verdana; font-size:6pt"> 
		<tr>
			<td class="smallcaption_pt6 left"><b>'.$companyName.'</b></td>
			<td colspan="4" class="smallcaption_pt6 right">Printed By:</td>
			<td>'.$username.'</td>
			<td>&nbsp;</td>
			<td class="smallcaption_pt6 right">Printed On:</td>
			<td>'.$datetime.'</td>
			<td class="smallcaption_pt6 right">Page {PAGENO} of {nb}</td>
		</tr>
	</table>';
	
return $header;	
}			
ob_end_flush(); 
?>
