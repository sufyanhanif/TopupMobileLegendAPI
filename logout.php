<?php
session_start();
session_destroy();
header('Location: /topup/index.php');
exit();
?>
