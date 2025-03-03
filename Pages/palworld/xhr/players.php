<?php
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://192.168.1.2:8212/v1/api/players',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 5,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//  CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_HTTPHEADER => array(
      'Accept: application/json'
    ),
));
curl_setopt($curl, CURLOPT_POSTFIELDS, [
    'username' => 'admin',
    'password' => 'Hbhv22kv'
]);

$response = curl_exec($curl);
if($response === false)
{
    var_dump(curl_error($curl));
    var_dump(curl_errno($curl));
}

curl_close($curl);



var_dump(date('U'));
var_dump($response);
?>

PLAYERS!!!