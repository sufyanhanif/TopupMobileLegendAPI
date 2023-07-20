<?php
$requestMethod = $_SERVER["REQUEST_METHOD"];
include('api/Rest.php');
$api = new Rest();
switch($requestMethod) {
    case 'GET':
        $pblId = '';
        if($_GET['id']) {
            $pblId = $_GET['id'];
        }
        $api->getPembeli($pblId);
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
?>