<?php
include('../api/Rest.php');
session_start();
$email = $_SESSION['email'];
$api = new Rest();

foreach ($api->login($email) as $x) {
    $akses_id = $x['akses_id'];
    if($akses_id == '1'){
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Menambah data Produk</title>

  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">

  </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        <?php
        include "navbar.php";
        ?>        
  <section class="content">



    <div class="container">
        <!-- Small boxes (Stat box) -->
        <div class="card mt-4">
        <div class="col-md-12">
        <div class="card">
            <div class="card-header" style="background-color: black;">
                <h4 style="color:white">Menambah Produk
                    <a href="index.php" class="btn btn-danger float-end">Kembali</a>
                </h4>
            </div>
            <div class="card-body">
                <form action="add.php" method="POST">

                    <div class="mb-3">
                        <label>Nama Produk</label>
                        <input type="text" name="product_name" id="product_name" placeholder="Nama Produk" class="form-control">
                    <div class="mb-3">
                        <label>Harga</label>
                        <input type="text" name="price" id="price" placeholder="Harga" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="status">Status</label>
                        <select class="form-control" name="status" id="status">
                              <option value="active">Active</option>
                              <option value="unactive">Unactive</option>
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
        <div>
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