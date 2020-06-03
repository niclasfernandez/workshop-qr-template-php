<?php

http_response_code(200);

$mp_endpoint = 'https://api.mercadopago.com/mpmobile/instore/qr/520255910/pythontest?access_token=APP_USR-8784583960835302-012920-171e94e7f45cc4e31e3cbcd15fb591ef-520255910';

$ch = curl_init($mp_endpoint);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);

?>