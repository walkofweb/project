<?php 
// error_reporting(1);

    

require_once "../../vendor/autoload.php";
require '../../vendor/phpmailer/phpmailer/src/Exception.php';
require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../vendor/phpmailer/phpmailer/src/SMTP.php';	

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$flag=1 ; // 1 for local system 

if($flag==1){

	$firstName = isset($_POST['firstName'])?$_POST['firstName']:'Amit' ;
	$lastName = isset($_POST['lastName'])?$_POST['lastName']:'Shukla' ;
	$partnerEmail = isset($_POST['partnerEmail'])?$_POST['partnerEmail']:'amitshukla.intigate@gamil.com' ;
	$mobileN = isset($_POST['phoneNumber'])?$_POST['phoneNumber']:'7289057538' ; 
	$partnerTitle = isset($_POST['partnerTitle'])?$_POST['partnerTitle']:'Test' ; 
	$partnerMessage = isset($_POST['partnerMessage'])?$_POST['partnerMessage']:'Test Once' ;


	$subject = "Test Message";
   $msg='<div style="width: 100%;max-width: 980px;background: #11161A;/* padding: 20px; */margin: auto;">
		<div style="text-align: center;border-bottom: 1px solid rgba(255, 255, 255, 0.3);padding: 15px 0px;">
			<img src="https://www.casanft.club/images/logo.png" alt="">
		</div>
		<div style="width: max-content;margin: auto;">
			<div style="color: #fff;padding: 70px 20px;">
				<div style="padding-bottom: 15px;font-size: 14px;">
					<strong>Name : </strong>
					<span>'.$firstName.' '.$lastName.'</span>
				</div>
				<div style="padding-bottom: 15px;font-size: 14px;">
					<strong>Partner Email : </strong>
					<span>'.$partnerEmail.'</span>
				</div>
				<div style="padding-bottom: 15px;font-size: 14px;">
					<strong>Phone Number : </strong>
					<span>'.$mobileN.'</span>
				</div>
				<div style="padding-bottom: 15px;font-size: 14px;">
					<strong>Title : </strong>
					<span>'.$partnerTitle.'</span>
				</div>
				<div style="padding-bottom: 15px;font-size: 14px;">
					<strong>Message : </strong>
					<span>'.$partnerMessage.'</span>
				</div>
			</div>
		</div>
	
		<div style="text-align: center;border-top: 1px solid rgba(255, 255, 255, 0.3);padding: 10px 0px 30px 0px;">
			<div>
				<h3 style="font-weight: 700;font-size: 20px;line-height: 20px;margin-bottom: 20px; color: #fff;">CasaNFT
				</h3>
				<p style="font-size: 16px;line-height: 26px;margin: 0;color: #94999C;">Enjoy the NFT Houses with CASANFT
				</p>
			</div>
	
			<div style="margin-top: 30px;">
				<ul style="padding: 0;margin: 0 -10px; list-style-type: none;">
					<li style="display: inline-block;padding: 0 10px;"> <a href="https://www.facebook.com/evanluthra/"
							style="width: 20px;height: 20px;display: inline-block;border: 1px solid rgba(255, 255, 255, 0.3);border-radius: 40px;padding: 8px;"><img
								src="images/facebook-app-symbol (1).svg" alt="" style="width: 20px;"></a></li>
					<li style="display: inline-block;padding: 0 10px;"> <a href="https://www.instagram.com/evanluthra/"
							style="width: 20px;height: 20px;display: inline-block;border: 1px solid rgba(255, 255, 255, 0.3);border-radius: 40px;padding: 8px;"><img
								src="images/instagram.svg" alt="" style="width: 20px;"></a></li>
					<li style="display: inline-block;padding: 0 10px;"> <a href="https://twitter.com/EvanLuthra"
							style="width: 20px;height: 20px;display: inline-block;border: 1px solid rgba(255, 255, 255, 0.3);border-radius: 40px;padding: 8px;"><img
								src="images/twitter.svg" alt="" style="width: 20px;"></a></li>
				</ul>
			</div>
	
		</div>
	
	</div>' ;


    // $msg=" Name :".$firstName." ".$lastName."<br>";
    // $msg.=" Partner Email :".$partnerEmail."<br>";
    // $msg.=" Phone Number :".$mobileN."<br>";
    // $msg.=" Title :".$partnerTitle."<br>";
    // $msg.=" Message :".$partnerMessage."<br>";
   
	echo $msg ;
	if($flag==1){
		sendEmail($subject,$msg);
	}
	echo "succ" ;
	//return true ;
}



   
function sendEmail($subject,$content){


$mail = new PHPMailer();
$mail->IsSMTP();

$mail->Mailer = "smtp";
$mail->SMTPDebug  = 1;  
$mail->SMTPAuth   = TRUE;
$mail->SMTPSecure = "tls";
$mail->Port       = 587;
$mail->Host       = "smtp.gmail.com";
//$mail->Host = 'tls://smtp.gmail.com:587';
$mail->Username   = "vikas.intigate@gmail.com";
$mail->Password   = "jrzopgaeiaxeejii";
$mail->IsHTML(true);
$mail->AddAddress("amitshukla.intigate@gmail.com");
//$mail->AddAddress("avnish.mishra@intigate.in");
$mail->SetFrom("vikas.intigate@gmail.com", "Intigate");


// $mail->AddReplyTo("reply-to-email@domain", "reply-to-name");
// $mail->AddCC("cc-recipient-email@domain", "cc-recipient-name");
$mail->Subject = $subject;
$content = $content ;


$mail->MsgHTML($content); 
if(!$mail->Send()) {
	return false ;
  //echo "Error while sending Email.";
 // var_dump($mail);
} else {
	return true ;
 // echo "Email sent successfully";
}


exit ;

}




 ?>