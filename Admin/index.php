<?php

include('../api/Rest.php');
session_start();
$email = $_SESSION['email'];
$api = new Rest();
 

    function http_request($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
    
    $rest = new Rest(); 
    $pembelian = http_request("http://localhost/topup/read.php?id=");
    $pembelian = json_decode($pembelian, TRUE);

    if (isset($_SESSION['error'])) {
      $error = $_SESSION['error'];
      unset($_SESSION['error']);
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Success'])) {
        $id = $_POST['id'];
        
        // Update the status to "Paid" using the API endpoint
        $url = 'http://localhost/topup/update.php';
        $data = array(
            'id' => $id,
            'status' => 'Success'
        );
        
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($curl);
        $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        
        curl_close($curl);
        
        if ($response !== false && $httpStatus == 200) {
            $_SESSION['success_message'] = "Status pembelian berhasil diubah.";
            // Redirect to the same page to show the updated status
            header("Location: http://localhost/topup/admin/index.php");
            exit;
        } else {
            $_SESSION['error_message'] = "Terjadi kesalahan pada koneksi.";
            echo "Error: kesalahan koneksi.";
            exit;
        }
    } elseif (isset($_POST['Failed'])) {
        $id = $_POST['id'];
        
        // Update the status to "Failed" using the API endpoint
        $url = 'http://localhost/topup/update.php';
        $data = array(
            'id' => $id,
            'status' => 'Failed'
        );
        
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($curl);
        $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        
        curl_close($curl);
        
        if ($response !== false && $httpStatus == 200) {
            $_SESSION['success_message'] = "Status pembelian berhasil diubah.";
            // Redirect to the same page to show the updated status
            header("Location: http://localhost/topup/admin/index.php");
            exit;
        } else {
            $_SESSION['error_message'] = "Terjadi kesalahan pada koneksi.";
            echo "Error: kesalahan koneksi.";
            exit;
        }
    }
}
    

    foreach ($api->login($email) as $x) {
        $akses_id = $x['akses_id'];
        if($akses_id == '1'){
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>pembelian Polines</title>

  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">

</head>
<body>
        <?php
        include "navbar.php";
        ?>
      <div class="container">
        <!-- Small boxes (Stat box) -->
        <div class="card mt-4">
            <div class="col-md-12">
                <div class="card" >
                    <div class="card-header" style="background-color: black;">
                        <h4  style="color: white;">Data Topup
                        </h4>
                    </div>
                    <div class="card-body">

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal Transaksi</th>
                                    <th>Username</th>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Harga Produk</th>
                                    <th>Jumlah Produk</th>
                                    <th>Total Pembayaran</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pembelian as $pembelian){ ?>
                                    <tr>
                                                <td><?= $pembelian['transaction_date']; ?></td>
                                                <td><?= $pembelian['username']; ?></td>
                                                <td><?= $pembelian['product_code']; ?></td>
                                                <td><?= $rest->getProductName($pembelian['product_code']); ?></td>
                                                <td><?= $rest->getProductPrice($pembelian['product_code']); ?></td>
                                                <td><?= $pembelian['qty']; ?></td>
                                                <td><?= $pembelian['total_price']; ?></td>
                                                <td><?= $pembelian['payment']; ?></td>
                                                <td><?= $pembelian['status']; ?></td>
                                                <td>
                                                <?php if ($pembelian['status'] === 'Paid') { ?>
                                                    <form method="POST" action="">
                                                    <input type="hidden" name="id" value="<?= $pembelian['id']; ?>">
                                                    <button type="submit" name="Success" class="btn btn-success">Success</button>
                                                    <button type="submit" name="Failed" class="btn btn-danger">Cancel</button>
                                                    </form>
                                                <?php } elseif ($pembelian['status'] === 'Pending') { ?>
                                                    <button name="Pending" class="btn btn-warning" disabled>Pending</button>
                                                <?php } elseif ($pembelian['status'] === 'Success') { ?>
                                                    <button name="Success" class="btn btn-success" disabled>Success</button>
                                                <?php } elseif ($pembelian['status'] === 'Failed') { ?>
                                                    <button name="Failed" class="btn btn-danger" disabled>Failed</button>
                                                <?php } ?>
                                                
                                                </td>
                                            </tr>
                                            <?php } ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>


    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    </div><!-- /.container-fluid -->

    </body>
</html>
<?php
    } else {
        echo "Anda Harus Login!";
        header('location:login.php');
        exit;
    }
}
?>