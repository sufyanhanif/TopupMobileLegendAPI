<?php
    include('api/Rest.php');
    $api = new Rest();
    $email = $_POST['email'];
    $password = ($_POST['password']);

    foreach($api->login($email) as $x){
        session_start();
        $_SESSION["email"] = $email;
        $_SESSION["password"] = $password;
        $email = $x['email'];
        $pass = $x['password'];

        if(($email==$email) AND ($password==$pass)){
            header('location: admin/index.php');
        } else{
            header('location:login.php');
        }
    }
?>