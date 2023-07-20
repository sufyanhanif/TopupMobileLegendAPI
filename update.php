<?php
$requestMethod = $_SERVER["REQUEST_METHOD"];
include('api/Rest.php');
$api = new Rest();

switch ($requestMethod) {
    case 'POST':
        $data = array(
            'id' => $_POST['id'],
            'status' => $_POST['status']
        );
        $api->updatePembelian($data);
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
?>