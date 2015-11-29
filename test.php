
<?php

$url = 'https://muni.mapleta.com/muni/ws/ping';
$data = array('key1' => 'value1', 'key2' => 'value2');

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => "
"
    ),
);
$url = 'https://muni.mapleta.com/muni/ws/ping';
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "<Request>            
 <value>???ffff</value>
<timestamp>1446049431924</timestamp>
<signature>IRe0mawp5QZzHGCnnkbLWw==</signature>
</Request>");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   
$response = curl_exec($ch);
var_dump($response);
curl_close($ch);
