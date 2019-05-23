<?php
include_once("site.php");
include_once AP_MODEL . 'dbconfig.php';
include_once CLASSPATH . 'ClassAutoloader.inc';
$autoloader		=	new ClassAutoloader();  
	
$conn 			= new connectUpdate();
$recon 			= new recordUpdate();
$subscribers	= array();
$newsletterId	= array();
 
$expiry_left 	= 0;

$timezone = "Asia/Manila";
putenv("TZ=".$timezone);

$date_today = strtotime("now");

set_time_limit(55);

$arrayValues 	= array('id','email','newsletterid');
$retArray 		= $recon->retrieveEntry("maillist", $arrayValues, "", " status='queued' LIMIT 5 ");
foreach ($retArray as $retIndex => $retValue) {
            $$retIndex 			= 	$retValue;
            $mainArr 			= 	explode('|', $$retIndex);
            $nid 				= 	$mainArr[0];
            $email 				= 	$mainArr[1];
            $newsid				= 	$mainArr[2];
			$subscribers[$nid] 	= 	$email;
			$newsletterId[$nid] = 	$newsid;
}
		
//Loop all subscribers  
foreach($subscribers as $id => $mail){
	
	$mailitem 	= 	$newsletterId[$id];
	
	//Retrieving News Letter	
	$arrayValues2 		=	array('title','content');
	$retArray2 			=	$recon->retrieveEntry("newsletter", $arrayValues2, "", "id='$mailitem'");
	foreach ($retArray2 as $retIndex2 => $retValue2){
		$$retIndex2 	=	$retValue2;
		$mainArr2 		=	explode('|', $$retIndex2);
		$title		 	=	$mainArr2[0];
		$content	 	=	$mainArr2[1];
		$msgSubject		=	$title;
		$msgBody		=	$content;
	}

	//Retrieving Admin Email and Info
	$arrayValues5 		=	array('name','remarks1');
	$retArray5 			=	$recon->retrieveEntry("reference", $arrayValues5, "", "ref_name='admin_email'");
	foreach ($retArray5 as $retIndex5 => $retValue5) {
			$$retIndex5 	=	$retValue5;
			$mainArr5 		=	explode('|', $$retIndex5);
			$adminemail 	=	$mainArr5[0];
			$adminname		=	$mainArr5[1];			
	}
	
	//Retrieving Subscriber's Name
	$arrayValues3 		=	array('name');
	$retArray3 			=	$recon->retrieveEntry("members", $arrayValues3, "", "email='$mail'");
	foreach ($retArray3 as $retIndex3 => $retValue3) {
			$$retIndex3 	=	$retValue3;
			$mainArr3 		=	explode('|', $$retIndex3);
			$subscriberName	=	$mainArr3[0];			 
	}
	
	$mailer		=	new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
	$mailer->IsSendmail(); // telling the class to use SendMail transport
	try {					
		  $mailer->AddReplyTo($mail, $subscriberName);
		  $mailer->AddAddress($mail, $subscriberName);
		  $mailer->SetFrom($adminemail, $adminname);
		  $mailer->AddReplyTo($mail, $subscriberName);
		  $mailer->Subject = $msgSubject;
		  //$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically 
		  //$mailer->AltBody = strip_tags($msgBody, "<strong><em><p>");
		  $mailer->AltBody = strip_tags(html_entity_decode($msgBody));
		  $mailer->MsgHTML(stripslashes(html_entity_decode($msgBody)));
		  $mailer->Send();
	} catch (phpmailerException $e) {
		  $e->errorMessage(); //Pretty error messages from PHPMailer
	} catch (Exception $e) {
	      $e->getMessage(); //Boring error messages from anything else!
	}
	
	$recon->deleteRecord("maillist", "id='$id'");
	//sleep(5);
}
	
	
?>