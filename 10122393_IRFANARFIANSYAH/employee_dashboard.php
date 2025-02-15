<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 4) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM employees WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

$sql = "SELECT COUNT(*) as pending_leave_requests FROM leave_requests WHERE employee_id = ? AND status = 'Menunggu'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee['employee_id']);
$stmt->execute();
$result = $stmt->get_result();
$pending_leave_requests = $result->fetch_assoc()['pending_leave_requests'];

$content = '
<h1>Dashboard Karyawan</h1>
<div class="row mt-4">
  <div class="col-md-6">
      <div class="card">
          <div class="card-body">
              <h5 class="card-title">Informasi Pribadi</h5>
              <p><strong>Nama:</strong> ' . $employee['first_name'] . ' ' . $employee['last_name'] . '</p>
              <p><strong>Posisi:</strong> ' . $employee['position'] . '</p>
              <p><strong>Tanggal Bergabung:</strong> ' . $employee['hire_date'] . '</p>
          </div>
      </div>
  </div>
  <div class="col-md-4">
      <div class="card">
          <div class="card-body">
              <h5 class="card-title">Permintaan Cuti Menunggu</h5>
              <p class="card-text">' . $pending_leave_requests . '</p>
          </div>
      </div>
  </div>
</div>
<div class="mt-4">
  <h2>Tindakan</h2>
  <ul>
      <li><a href="view_profile.php">Lihat Data Pribadi</a></li>
      <li><a href="edit_profile.php">Ajukan Perubahan Data Pribadi</a></li>
      <li><a href="leave_request.php">Ajukan Permintaan Cuti</a></li>
      <li><a href="view_leave_status.php">Lihat Status Permintaan Cuti</a></li>
  </ul>
</div>
';

include 'layout.php';
?>

