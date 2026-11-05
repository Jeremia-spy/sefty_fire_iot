<?php
session_start();
unset($_SESSION['penghuni']);
unset($_SESSION['nomor_kamar']);
session_destroy();
header("Location: login_penghuni.php");
exit;
?>