<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "10122393_if-11_kepegawaian";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}
