<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = 'http://localhost/topup/create.php';
    $data = array(
        'username' => $_POST['username'],
        'product_code' => $_POST['product_code'],
        'qty' => $_POST['qty'],
        'payment' => $_POST['payment'],
        'total_price' => $_POST['total_price']
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
            $_SESSION['success_message'] = "Pembelian berhasil ditambahkan.";
            $id = $responseData['id']; // Mendapatkan ID pembelian baru
            header("Location: http://localhost/topup/paid.php?id=$id");
            exit;
        } elseif (isset($responseData['status']) && $responseData['status'] == 0) {
            $_SESSION['error_message'] = "Gagal menambahkan pembelian.";
            echo "Error: NIM already exists.";
            exit;
        } else {
            $_SESSION['error_message'] = "Terjadi kesalahan pada server.";
            echo "Error: NIM already exists.";
            exit;
        }
    } else {
        $_SESSION['error_message'] = "Terjadi kesalahan pada koneksi.";
        echo "Error: kesalahan konesi.";
        exit;
    }
}
?>
