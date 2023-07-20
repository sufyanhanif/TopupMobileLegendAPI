<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = 'http://localhost/topup/admin/create.php';
    $data = array(
        'product_name' => $_POST['product_name'],
        'price' => $_POST['price'],
        'status'=> $_POST['status'],
    );

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);

    if ($response !== false && $httpStatus == 200) {
        $responseData = json_decode($response, true);
        if (isset($responseData['status']) && $responseData['status'] == 1) {
            header("Location: http://localhost/topup/admin/produk.php");
            exit;
        } elseif (isset($responseData['status']) && $responseData['status'] == 0) {
            header("Location: http://localhost/topup/admin/produk_create.php");
            echo "Error: NIM already exists.";
            exit;
        } else {
            header("HTTP/1.1 500 Internal Server Error");
            echo "Error: Nim yang kamu buat sudah ada.";
            exit;
        }
    }
}
?>