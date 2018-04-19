<?php
    header('content-type:text/html;charset=utf8');
    $curl = curl_init();
    $data = array('img'=>'@'. dirname(__FILE__).'/img/login.gif');
    curl_setopt($curl, CURLOPT_URL, "filezilla/uploadimg.php");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($curl);
    curl_close($curl);
    echo json_decode($result);
?>