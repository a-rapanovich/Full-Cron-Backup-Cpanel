<?php

// Данные для входа в Cpanel
$cpuser = ""; 
$cppass = ""; 
$domain = ""; 
//Скин Cpanel
$skin = "paper_lantern"; 

// FTP
$ftpuser = ""; 
$ftppass = ""; 
$ftphost = ""; 
// passiveftp , ftp
$ftpmode = "ftp"; 

// Почта для уведомления
$notifyemail = ""; 

// SSL
$secure = 1; 

// Отладка в лог
$debug = 0;

if ($secure) {
   $url = "ssl://".$domain;
   $port = 2083;
} else {
   $url = $domain;
   $port = 2082;
}

$socket = fsockopen($url,$port);
if (!$socket) { echo "Не удалось открыть сокетное соединение... Выход!\n"; exit; }

$authstr = $cpuser.":".$cppass;
$pass = base64_encode($authstr);

$params = "dest=$ftpmode&email=$notifyemail&server=$ftphost&user=$ftpuser&pass=$ftppass&submit=Generate Backup";

fputs($socket,"POST /cpsess1488233654/frontend/".$skin."/backup/dofullbackup.html?".$params." HTTP/1.0\r\n");
fputs($socket,"Host: $domain\r\n");
fputs($socket,"Authorization: Basic $pass\r\n");
fputs($socket,"Connection: Close\r\n");
fputs($socket,"\r\n");

while (!feof($socket)) {
  $response = fgets($socket,4096);
  if ($debug) echo $response;
}
fclose($socket);

?>