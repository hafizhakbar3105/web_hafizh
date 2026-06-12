<?php
session_start();

// 1. Hancurkan semua session login admin
$_SESSION = array();
session_destroy();

// 2. Alihkan halaman keluar ke root folder menuju home.php
echo "<script>alert('Sistem: Anda telah berhasil keluar.'); window.location='../home.php';</script>";
exit;
?>