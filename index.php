<?php
include('api/Rest.php');

$rest = new Rest();
$products = $rest->getProductData();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Memperbarui product_code berdasarkan data yang dikirim melalui formulir
    $productCode = $_POST['product_code'];
    $selectedProduct = null;
    
    // Cek apakah productCode ada dalam array $products
    if (array_key_exists($productCode, $products)) {
        // Cek apakah product dengan productCode tersebut aktif
        if ($products[$productCode]['status'] === 'active') {
            $selectedProduct = $products[$productCode];
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Game Checker</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap">
    <style>
        .form-group {
            display: inline-block;
            margin-right: 10px;
        }

        .btn-primary {
            display: inline-block;
            margin-bottom: 8px;
        }

        .panel {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
            text-align: center;
            margin-bottom: 8px;
            margin-top: 8px;
        }

        .panel.selected {
            background-color: #004a9a;
        }

        .panel-container {
            float: left;
            text-align: center;
        }

        .panel:hover {
            background-color: #0056b3;  
        }

        .card-title {
            font-family: 'Roboto', sans-serif;
            font-weight: bold;
            font-size: 18px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .card {
            margin-top: 20px;
            margin-left: 10px;
            margin-right: 10px;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .card-body {
            padding: 20px;
        }

        .order-summary {
            float: right;
        }

        .centered-image {
            display: flex;
            justify-content: center;
        }

        .rounded-image {
            border-radius: 15px;
            overflow: hidden;
        }

    </style>
</head>
<body>
        <?php
        include "navbar_user.php";
        ?>
    <div class="container">
        <h1 class="mt-5">Mobile Legend</h1>
        <div class="card mt-4 order-summary">
        <div class="card-body">
            <div class="centered-image">
                <div class="rounded-image">
                    <img src="logo_ml.jpeg" alt="Logo" width="150" height="150">
                </div>
            </div>
            <br>
            <h5 class="card-title">Tutorial Top Up</h5>
                <a>1. Masukan Id dan Server Lalu tekan Check </a>
                <br>
                <a>2. Jika Username sudah muncul, pilih Diamond</a>
                <br>
                <a>3. Masukan Jumlah yang diinginkan</a>
                <br>
                <a>4. Pilih Metode Pembayaran lalu tekan Bayar</a>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Your ID</h5>
                <form>
                    <div class="form-group">
                        <label for="id">ID:</label>
                        <input type="text" class="form-control" name="id" id="userId" required>
                    </div>
                    <div class="form-group">
                        <label for="server">Server:</label>
                        <input type="text" class="form-control" name="server" id="serverId" required>
                    </div>

                    <button type="button" class="btn btn-primary" onclick="clickApi()">Check</button>
                </form>

                <form action="add.php" method="POST">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" name="username" id="username" readonly required>
                    </div>

                    <div class="card mt-4">
                        <div class="card-body">
                            <h5 class="card-title">Diamond</h5>
                            <div class="panel-container">
                            <?php foreach ($products as $productCode => $product): ?>
                                <?php if ($product['status'] === 'active'): ?>
                                    <div class="panel <?php echo isset($selectedProduct) && $selectedProduct['product_code'] == $productCode ? 'selected' : ''; ?>"
                                        onclick="updateSelectedProduct(this)" data-price="<?php echo $product['price']; ?>" data-product-id="<?php echo $product['id']; ?>">
                                        <h4 class="panel-title"><?php echo $product['product_name']; ?></h4>
                                        <p class="panel-info">Price: Rp<?php echo $product['price']; ?></p>
                                        <input type="hidden" name="product_code" id="product_code_<?php echo $productCode; ?>" value="<?php echo $product['id']; ?>">
                                    </div>
                                <?php endif; ?>
                            <?php endforeach;?>
                            <input type="hidden" class="form-control" name="product_code" id="product_code">
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="qty">Jumlah:</label>
                                <input type="text" class="form-control" name="qty" id="qty" required oninput="calculateTotalPayment()">
                            </div>
                            <div class="form-group">
                                <label for="total_price">Total Pembayaran:</label>
                                <input type="text" class="form-control" name="total_price" id="total_price" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="payment">Metode Pembayaran:</label>
                                <select class="form-control" name="payment" id="payment">
                                    <option value="Dana">Dana</option>
                                    <option value="BCA">BCA</option>
                                    <option value="BNI">BNI</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <button type="submit" class="btn btn-primary">Bayar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function clickApi() {
            var userId = $('#userId').val();
            var serverId = $('#serverId').val();

            const settings = {
                async: true,
                crossDomain: true,
                url: 'https://id-game-checker.p.rapidapi.com/mobile-legends/'+userId+'/'+serverId,
                method: 'GET',
                headers: {
                    'X-RapidAPI-Key': '74960f0311msh264cf44267c81c0p18b48bjsn09b0751d75b3',
		            'X-RapidAPI-Host': 'id-game-checker.p.rapidapi.com'
                }
            };

            $.ajax(settings).done(function (response) {
                console.log(response);

                $('#username').val(response.data.username);
            });
        }

        function updateSelectedProduct(panel) {
            $('.panel').removeClass('selected');
            $(panel).addClass('selected');

            var selectedProductId = $(panel).find('input[name="product_code"]').val();
            $('#product_code').val(selectedProductId);

            calculateTotalPayment();
         }

        function calculateTotalPayment() {
            var quantity = parseInt($('#qty').val());
            var price = parseFloat($('.panel.selected').data('price'));
            if (!isNaN(quantity) && !isNaN(price)) {
                var totalPayment = quantity * price;
                $('#total_price').val('Rp' + totalPayment.toFixed(2));
            } else {
                $('#total_price').val('');
            }
        }

        // Set initial selected product price based on URL parameter
        $(document).ready(function() {
            var selectedProductCode = "<?php echo isset($selectedProduct) ? $selectedProduct['product_code'] : ''; ?>";
            if (selectedProductCode !== '') {
                $('.panel[data-product-code="' + selectedProductCode + '"]').addClass('selected');
            }
            calculateTotalPayment();
        });
    </script>

</body>
</html>
