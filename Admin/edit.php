<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = 'http://localhost/topup/admin/update.php';
    $data = array(
        'id' => $_POST['id'],
        'product_name' => $_POST['product_name'],
        'price' => $_POST['price'],
        'status' => $_POST['status'],
    );

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
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
        } else {
            echo "Error: Parameter 'id' is missing.";
        }
    }
}
?>
