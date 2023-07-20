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
    

    $produk = http_request("http://localhost/topup/admin/read.php?id=");
    $produk = json_decode($produk, TRUE);

    if (isset($_SESSION['error'])) {
      $error = $_SESSION['error'];
      unset($_SESSION['error']);
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
  <title>produk Polines</title>

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
                        <h4  style="color: white;">Data Produk
                        <a href="produk_create.php" class="btn btn-primary float-end">Tambah Produk</a>
                    </h4>
                        
                    </div>
                    <div class="card-body">

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Produk</th>
                                    <th>Harga Produk</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($produk as $produk){ ?>
                                    <tr>
                                                <td><?= $produk['id']; ?></td>
                                                <td><?= $produk['product_name']; ?></td>
                                                <td>Rp<?= $produk['price']; ?></td>
                                                <td><?= $produk['status']; ?></td>
                                                <td>
                                                <a href="produk_edit.php?id=<?= $produk['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
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
    }
}
?>