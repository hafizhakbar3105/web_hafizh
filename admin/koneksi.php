<?php
$host = "localhost";
$user = "ifummiid_kelasa";
$pass = "pemweb_db_a";
$db   = "ifummiid_kelasa";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>