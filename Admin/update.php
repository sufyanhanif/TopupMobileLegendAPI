<?php
$requestMethod = $_SERVER["REQUEST_METHOD"];
include('../api/Rest.php');
$api = new Rest();
switch($requestMethod) {
    case 'POST':
        $api->updateProduct($_POST);
        break;
    case 'PUT':
        parse_str(file_get_contents("php://input"), $_PUT);
        $api->updateProduct($_PUT);
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
?>
