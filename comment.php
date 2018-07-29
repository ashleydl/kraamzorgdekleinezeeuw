<?php  
if (isset($_POST['naam']) && isset($_POST['bericht']) && isset($_POST['g-recaptcha-response'])) {  

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
		$naam = preg_replace("/\r\n|\r|\n/",'<br/>',htmlspecialchars($_POST['naam']));  
		$bericht = preg_replace("/\r\n|\r|\n/",'<br/>',htmlspecialchars($_POST['bericht']));  
		  
		$file = 'comments.txt';
		// Open the file to get existing content
		$current = file_get_contents($file);
		// Append a new person to the file
		$current .= $naam . ";";
		$current .= $bericht . "\n";
		// Write the contents back to the file
		file_put_contents($file, $current);  
		
		$arr = array ('success'=>'true','reason'=>'');
		echo json_encode($arr);
		exit;
	}
}
$arr = array ('success'=>'false','reason'=>'post variables not set');
echo json_encode($arr);
exit; 
?> 