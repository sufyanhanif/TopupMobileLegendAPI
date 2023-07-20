<?php
class Rest{
    private $host = 'localhost';
    private $user = 'root';
    private $password = "";
    private $database = "topup";
    private $pblTable = 'pembelian';
    private $prdTable = 'product';
    private $userTable = 'user';
    private $dbConnect = false;

    // skrip fungsi-fungsi letakkan/sisipkan disini

    public function __construct(){
        if(!$this->dbConnect){
            $conn = new mysqli($this->host, $this->user, $this->password, $this->database);
            if($conn->connect_error){
                die("Error failed to connect to MySQL: " . $conn->connect_error);
            }else{
                $this->dbConnect = $conn;
            }
        }
    }

    public function getProductData() {
        $query = "SELECT id, product_name, price, status FROM " . $this->prdTable . " WHERE status = 'active'";
        $result = $this->dbConnect->query($query);
        $data = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function login($email) {
        $query = "SELECT * FROM " . $this->userTable . " WHERE email = '$email'";
        $result = $this->dbConnect->query($query);
    
        if ($result) { // Check if the query was executed successfully
            if ($result->num_rows == 0) {
                echo "<b>Data user tidak ada</b>";
                $hasil = [];
                header('location: login.php');
            } else {
                $hasil = array();
                while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                    $hasil[] = $row;
                }
            }
            $result->free();
        } else {
            echo "Query execution failed: " . $this->dbConnect->error;
        }
    
        return $hasil;
    }
    

    public function getPembeli($pblId='') {
        $sqlQuery = '';
        if($pblId) {
            $sqlQuery = "WHERE id = '".$pblId."'";
        }
        
        $pblQuery = "SELECT id, transaction_date, username, product_code, qty, payment, total_price, status FROM ".$this->pblTable." $sqlQuery
        ORDER BY id ASC";
        
        $resultData = mysqli_query($this->dbConnect, $pblQuery);
        
        $pblData = array();
        
        while( $pblRecord = mysqli_fetch_assoc($resultData) ) {
            $pblData[] = $pblRecord;
        }

        header('Content-Type: application/json');
        echo json_encode($pblData);
    }

    public function insertPembelian($pblData) {
        $transactionDate = date('Y-m-d');  // Get current date
        $username = $pblData["username"];
        $productCode = $pblData["product_code"];
        $qty = $pblData["qty"];
        $payment = $pblData["payment"];
        $totalPrice = $pblData["total_price"];
        $status = "Pending";
    
        $stmt = $this->dbConnect->prepare("INSERT INTO " . $this->pblTable . " (transaction_date, username, product_code, qty, payment, total_price, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $transactionDate, $username, $productCode, $qty, $payment, $totalPrice, $status);
    
        if ($stmt->execute()) {
            $message = "Pembelian berhasil ditambahkan.";
            $status = 1;
            $id = $stmt->insert_id;
        } else {
            $id = null;
            $message = "Pembelian gagal ditambahkan.";
            $status = 0;
        }
    
        $pblResponse = array(
            'status' => $status,
            'status_message' => $message,
            'id' => $id
        );
    
        header('Content-Type: application/json');
        echo json_encode($pblResponse);
    }
    
    public function updatePembelian($data) {
        $id = $data['id'];
        $status = $data['status'];
    
        $stmt = $this->dbConnect->prepare("UPDATE " . $this->pblTable . " SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
    
        if ($stmt->execute()) {
            $message = "Status pembelian berhasil diubah.";
            $status = 1;
        } else {
            $message = "Status pembelian gagal diubah.";
            $status = 0;
        }
    
        $response = array(
            'status' => $status,
            'status_message' => $message
        );
    
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function getProductName($product_code) {
        $query = "SELECT product_name FROM " . $this->prdTable . " WHERE id = '" . $product_code . "'";
        $result = $this->dbConnect->query($query);
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['product_name'];
        } else {
            return '';
        }
    }
    
    public function getProductPrice($product_code) {
        $query = "SELECT price FROM " . $this->prdTable . " WHERE id = '" . $product_code . "'";
        $result = $this->dbConnect->query($query);
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['price'];
        } else {
            return '';
        }
    }

    public function getProduct($prdId='') {
        $sqlQuery = '';
        if($prdId) {
            $sqlQuery = "WHERE id = '".$prdId."'";
        }
        
        $pblQuery = "SELECT id, product_name, price, status FROM ".$this->prdTable." $sqlQuery
        ORDER BY id ASC";
        
        $resultData = mysqli_query($this->dbConnect, $pblQuery);
        
        $pblData = array();
        
        while( $pblRecord = mysqli_fetch_assoc($resultData) ) {
            $pblData[] = $pblRecord;
        }

        header('Content-Type: application/json');
        echo json_encode($pblData);
    }

    public function insertProduct($prdData){
        $prdNama=$prdData["product_name"];
        $prdPrice=$prdData["price"];
        $prdStatus=$prdData["status"];
        $prdQuery="
        
        INSERT INTO ".$this->prdTable."
        SET  product_name='".$prdNama."', Price='".$prdPrice."', status='".$prdStatus."'";
        mysqli_query($this->dbConnect, $prdQuery);
        
        if(mysqli_affected_rows($this->dbConnect) > 0) {
            $message = "Produk berhasil ditambahkan.";
            $status = 1;
        } else {
            $message = "Produk gagal ditambahkan.";
            $status = 0;
        }

        $prdResponse = array(
            'status' => $status,
            'status_message' => $message
        );
       
        header('Content-Type: application/json');
        echo json_encode($prdResponse);
    }

 
    public function updateProduct($prdData){
        if($prdData["id"]) {
            $prdNama=$prdData["product_name"];
            $prdPrice=$prdData["price"];
            $prdStatus=$prdData["status"];
            $prdQuery="UPDATE ".$this->prdTable." 
            SET product_name='".$prdNama."', price='".$prdPrice."', status='".$prdStatus."' WHERE id = '".$prdData["id"]."'";
            mysqli_query($this->dbConnect, $prdQuery);
        
            if(mysqli_affected_rows($this->dbConnect) > 0) {
                $message = "Mahasiswa sukses diedit.";
                $status = 1;
            } else {
                $message = "Mahasiswa gagal diedit.";
                $status = 0;
            }
        } else {
            $message = "Invalid request.";
            $status = 0;
        }

        $prdResponse = array(
            'status' => $status,
            'status_message' => $message
        );

        header('Content-Type: application/json');
        echo json_encode($prdResponse);
    }
   
    }

    
?>
