<?php  
if (isset($_POST['naam']) && isset($_POST['email']) && isset($_POST['bericht']) && isset($_POST['g-recaptcha-response'])) {  

	$captcha = $_POST['g-recaptcha-response'];
	$data = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LddVwkTAAAAAK4GjKtIvs2g9_v1HLF1NYiEAy9g&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
	$data = json_decode($data);
	
	if(!$data->success)
	{
		$arr = array ('success'=>'false','reason'=>'captcha');
		echo json_encode($arr);
		exit;
	}
	else
	{
		$naam = htmlspecialchars($_POST['naam']);  
		$email = htmlspecialchars($_POST['email']);  
		$bericht = htmlspecialchars($_POST['bericht']);  
		  
		$tijd = time();  
		$datum = strftime('%d/%m/%y %H:%M', $tijd);  
		$message = $naam.' met het e-mailadres '.$email.' stuurde op '.$datum.' het volgende bericht:  
------------------------------------
'.$bericht.'
------------------------------------';  
		$headers = 'From: ' . $email . "\r\n" .
		'Reply-To: ' . $email . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
		  
		mail('info@kraamzorgdekleinezeeuw.nl', 'Bericht van kraamzorgdekleinezeeuw.nl', $message, 'From: '.$email);  
		  
		
		$arr = array ('success'=>'true','reason'=>'');
		echo json_encode($arr);
		exit;
	}
}
$arr = array ('success'=>'false','reason'=>'post variables not set');
echo json_encode($arr);
exit; 
?> 