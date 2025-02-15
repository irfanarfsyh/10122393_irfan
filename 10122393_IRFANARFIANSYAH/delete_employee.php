<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
  header("Location: login.php");
  exit();
}

$employee_id = $_GET['id'] ?? null;

if (!$employee_id) {
  header("Location: manage_employees.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $sql = "DELETE e, u FROM employees e
          JOIN users u ON e.user_id = u.user_id
          WHERE e.employee_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $employee_id);

  if ($stmt->execute()) {
      $_SESSION['success'] = "Karyawan berhasil dihapus.";
  } else {
      $_SESSION['error'] = "Terjadi kesalahan saat menghapus karyawan. Silakan coba lagi.";
  }

  header("Location: manage_employees.php");
  exit();
}

$sql = "SELECT CONCAT(first_name, ' ', last_name) as full_name FROM employees WHERE employee_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

$content = '
<h2>Hapus Karyawan</h2>
<p>Apakah Anda yakin ingin menghapus karyawan: ' . $employee['full_name'] . '?</p>
<form method="post">
  <button type="submit" class="btn btn-danger">Konfirmasi Hapus</button>
  <a href="manage_employees.php" class="btn btn-secondary">Batal</a>
</form>
';

include 'layout.php';
?>

