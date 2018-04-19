<?php
$localfile = "php_homepage.txt";
$fp = fopen ($localfile, "r");
$arr_ip = gethostbyname(www.111cn.net);
echo $arr_ip;
$ftp = "ftp://".$arr_ip."/public_html/".$localfile; 
$ch = curl_init();
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_USERPWD, '***:****');
curl_setopt($ch, CURLOPT_URL, $ftp);
curl_setopt($ch, CURLOPT_PUT, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_INFILE, $fp);
curl_setopt($ch, CURLOPT_INFILESIZE, filesize($localfile));
$http_result = curl_exec($ch);
$error = curl_error($ch);
echo $error."<br>";
$http_code = curl_getinfo($ch ,CURLINFO_HTTP_CODE);curl_close($ch);
fclose($fp);
?> 