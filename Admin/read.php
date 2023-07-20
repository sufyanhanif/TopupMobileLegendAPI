<?php
$requestMethod = $_SERVER["REQUEST_METHOD"];
include('../api/Rest.php');
$api = new Rest();
switch($requestMethod) {
    case 'GET':
        $prdId = '';
        if($_GET['id']) {
            $prdId = $_GET['id'];
        }
        $api->getProduct($prdId);
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
?>