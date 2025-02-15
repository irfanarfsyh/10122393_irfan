<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
  header("Location: login.php");
  exit();
}

$user_id = $_GET['id'] ?? null;

if (!$user_id) {
  header("Location: manage_users.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $sql = "DELETE FROM users WHERE user_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $user_id);

  if ($stmt->execute()) {
      $_SESSION['success'] = "Pengguna berhasil dihapus.";
  } else {
      $_SESSION['error'] = "Terjadi kesalahan saat menghapus pengguna. Silakan coba lagi.";
  }

  header("Location: manage_users.php");
  exit();
}

$sql = "SELECT username FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$content = '
<h2>Hapus Pengguna</h2>
<p>Apakah Anda yakin ingin menghapus pengguna: ' . $user['username'] . '?</p>
<form method="post">
  <button type="submit" class="btn btn-danger">Konfirmasi Hapus</button>
  <a href="manage_users.php" class="btn btn-secondary">Batal</a>
</form>
';

include 'layout.php';
?>

