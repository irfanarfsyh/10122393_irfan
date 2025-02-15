<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
  header("Location: login.php");
  exit();
}

$department_id = $_SESSION['department_id'];

$sql = "SELECT COUNT(*) as total_employees FROM employees e JOIN users u ON e.user_id = u.user_id WHERE u.department_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $department_id);
$stmt->execute();
$result = $stmt->get_result();
$total_employees = $result->fetch_assoc()['total_employees'];

$sql = "SELECT COUNT(*) as pending_leave_requests FROM leave_requests lr 
      JOIN employees e ON lr.employee_id = e.employee_id 
      JOIN users u ON e.user_id = u.user_id 
      WHERE u.department_id = ? AND lr.status = 'Menunggu'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $department_id);
$stmt->execute();
$result = $stmt->get_result();
$pending_leave_requests = $result->fetch_assoc()['pending_leave_requests'];

$content = '
<h1>Dashboard Manajer Departemen</h1>
<div class="row mt-4">
  <div class="col-md-4">
      <div class="card">
          <div class="card-body">
              <h5 class="card-title">Total Karyawan di Departemen</h5>
              <p class="card-text">' . $total_employees . '</p>
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
      <li><a href="manage_department_employees.php">Kelola Karyawan Departemen</a></li>
      <li><a href="approve_leave_requests.php">Setujui Permintaan Cuti</a></li>
      <li><a href="view_department_reports.php">Lihat Laporan Departemen</a></li>
  </ul>
</div>
';

include 'layout.php';
?>

