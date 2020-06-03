<?php

session_start();
error_log("Session: ".session_id());
error_log(json_encode($_SESSION));

$external_reference = $_GET['external_reference'];
$status = $_SESSION['db'][$external_reference];

header('Content-type: application/json');
echo json_encode(["status" => $status]);

?>