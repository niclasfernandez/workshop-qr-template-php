<?php

session_start();

error_log("Session: ".session_id());

http_response_code(200);

$mp_endpoint = 'https://api.mercadopago.com/mpmobile/instore/qr/520255910/pythontest?access_token=APP_USR-8784583960835302-012920-171e94e7f45cc4e31e3cbcd15fb591ef-520255910';

$title = $_POST['title'];
$quantity = intval($_POST['quantity']);
$unit_price = intval($_POST['unit_price']);
$currency_id = 'ARS';

$fecha = date_create();
$time_stamp = date_timestamp_get($fecha);

$external_reference = 'niclas-' . $time_stamp;
$notification_url = 'https://niclas-mp-commerce-php.herokuapp.com/api/notifications.php?session_id='.session_id();

$items = array('title' => $title, 'currency_id' => $currency_id ,'quantity' => $quantity, 'unit_price' => $unit_price);

$json = array('external_reference' => $external_reference, 'notification_url' => $notification_url, 'items' => array($items));

$ch = curl_init($mp_endpoint);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);

header('Content-type: application/json');
echo json_encode($result, JSON_FORCE_OBJECT);

exit();
?>