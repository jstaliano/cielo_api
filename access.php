<?php

$curl = curl_init();


curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://cieloecommerce.cielo.com.br/api/public/v2/token',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_HTTPHEADER => array(
    'content-lenght:0',
    'Content-Type: application/json',
    'Authorization: Basic <base64>',
    'Content-Lenght: 1'),
  CURLOPT_MAXREDIRS => 10,  
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>array()
  
));
$response = curl_exec($curl);
curl_close($curl);
$res=json_decode($response, true);
//print_r($res);
//echo '<br>Access Token: <br>'.$res['access_token'];
//$res['access_token'];