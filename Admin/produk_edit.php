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
  echo $output;
}

  $id = $_GET['id'];
  $produk = http_request("http://localhost/topup/admin/read.php?id=$id");
  $editProduk = json_decode($produk, TRUE);

  foreach ($api->login($email) as $x) {
    $akses_id = $x['akses_id'];
    if($akses_id == '1'){
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit data Produk</title>

  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">

  </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        <?php
        include "navbar.php";
        ?>
  <section class="content">
    
<!-- Small boxes (Stat box) -->


    <div class="container">
        <!-- Small boxes (Stat box) -->
        <div class="card mt-4">
        <div class="col-md-12">
        <div class="card">
            <div class="card-header" style="background-color: black;">
                <h4 style="color:white">Edit Produk
                    <a href="index.php" class="btn btn-danger float-end">Kembali</a>
                </h4>
            </div>
            <div class="card-body">
                    <form action="edit.php" method="POST">
                    <input type="hidden" name="id" id="id" value="<?=$editProduk[0]['id'];?>" class="form-control">
                    <div class="mb-3">
                        <label>Nama Produk</label>
                        <input type="text" name="product_name" id="product_name" value="<?=$editProduk[0]['product_name'];?>" class="form-control">
                    <div class="mb-3">
                        <label>Harga</label>
                        <input type="text" name="price" id="price" value="<?=$editProduk[0]['price'];?>" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="status">Status</label>
                        <select class="form-control" name="status" id="status">
                        <option value="active" <?= $editProduk[0]['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="unactive" <?= $editProduk[0]['status'] === 'unactive' ? 'selected' : ''; ?>>Unactive</option>
                        </select>
                   </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>

                </form>
            </div>
        </div>
        </div>
        </div>
        </div>
        </div>

        <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
        
    </div><!-- /.container-fluid -->
    </section>
    </body>
</html>
<?php
    } else {
        echo "Anda Harus Login!";
        header('location:login.php');
    }
}
?>