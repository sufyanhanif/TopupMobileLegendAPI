<?php
function http_request($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
    echo $output;
}

$id = $_GET['id'];
$pembelian = http_request("http://localhost/topup/read.php?id=$id");
$dataPembelian = json_decode($pembelian, TRUE);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = 'http://localhost/topup/update.php';
    $data = array(
        'id' => $id,
        'status' => 'Paid'
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
        header("Location: http://localhost/topup/paid.php?id=$id");
        exit;
    } else {
        $_SESSION['error_message'] = "Terjadi kesalahan pada koneksi.";
        echo "Error: kesalahan koneksi.";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tabel Pembelian</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

    <style>
        .timer {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>

<body>
        <?php
        include "navbar_user.php";
        ?>
    <!-- Main content -->
    <section class="content">
        <div class="container">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header" style="background-color: black;">
                            <h4 style="color:white;">Detail Pembelian
                                <a href="index.php" class="btn btn-danger float-end">BACK</a>
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label>Id</label>
                                <p class="form-control">
                                    <?=$dataPembelian[0]['id'];?>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label>Username</label>
                                <p class="form-control">
                                    <?=$dataPembelian[0]['username'];?>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label>Total Pembayaran</label>
                                <p class="form-control">
                                    <?=$dataPembelian[0]['total_price'];?>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label>Method Payment</label>
                                <p class="form-control">
                                    <?=$dataPembelian[0]['payment'];?>
                                </p>
                            </div>
                            <?php if ($dataPembelian[0]['payment'] == 'BCA') : ?>
                                <div class="mb-3">
                                    <label>Nomor BCA</label>
                                    <p class="form-control">1234567890</p>
                                </div>
                            <?php elseif ($dataPembelian[0]['payment'] == 'BNI') : ?>
                                <div class="mb-3">
                                    <label>Nomor BNI</label>
                                    <p class="form-control">0987654321</p>
                                </div>
                            <?php elseif ($dataPembelian[0]['payment'] == 'Dana') : ?>
                                <div class="mb-3">
                                    <label>Nomor DANA</label>
                                    <p class="form-control">9876543210</p>
                                </div>
                            <?php endif; ?>
                            <div class="mb-3">
                                <label>Status</label>
                                <p id="status" class="form-control">
                                    <?=$dataPembelian[0]['status'];?>
                                </p>
                            </div>
                            
                            <?php if ($dataPembelian[0]['status'] != 'Paid' && $dataPembelian[0]['status'] != 'Failed' && $dataPembelian[0]['status'] != 'Success') : ?>
                                
                                <form id="paidForm" method="POST">
                                    <button type="submit" class="btn btn-primary">Paid</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Include Bootstrap JS -->
            <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    


        </div><!-- /.container-fluid -->
    </section>

</body>

</html>
