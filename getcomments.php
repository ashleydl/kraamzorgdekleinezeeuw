<?php  
header('Content-Type: text/plain');
$file = 'comments.txt';
$htmlText = nl2br(file_get_contents($file));
$return = '<hr style=\'border-width: 5px;border-color:white;\'>';
foreach(preg_split("/((\r?\n)|(\r\n?))/", $htmlText) as $line){
	if (!empty($line)) {
		$array = explode(';', $line, 2);
		$return .= '<h3>' . $array[0] . '</h3>';
		$return .= $array[1];
		$return .= '<hr style=\'border-width: 5px;border-color:white;\'>';
	}
} 
echo $return;
exit; 
?> 