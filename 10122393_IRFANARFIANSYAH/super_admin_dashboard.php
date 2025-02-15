<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
  header("Location: login.php");
  exit();
}

$sql = "SELECT COUNT(*) as total_users FROM users";
$result = $conn->query($sql);
$total_users = $result->fetch_assoc()['total_users'];

$sql = "SELECT COUNT(*) as total_employees FROM employees";
$result = $conn->query($sql);
$total_employees = $result->fetch_assoc()['total_employees'];

$sql = "SELECT COUNT(*) as total_departments FROM departments";
$result = $conn->query($sql);
$total_departments = $result->fetch_assoc()['total_departments'];

$sql = "SELECT d.department_id, d.department_name, 
               CONCAT(m.first_name, ' ', m.last_name) as manager_name,
               COUNT(e.employee_id) as employee_count
        FROM departments d
        LEFT JOIN users u ON d.department_id = u.department_id AND u.role_id = 3
        LEFT JOIN employees m ON u.user_id = m.user_id
        LEFT JOIN users ue ON d.department_id = ue.department_id
        LEFT JOIN employees e ON ue.user_id = e.user_id
        GROUP BY d.department_id
        ORDER BY d.department_name";
$result = $conn->query($sql);

$content = '
<h1>Dashboard Super Admin</h1>
<div class="row mt-4">
  <div class="col-md-4">
      <div class="card">
          <div class="card-body">
              <h5 class="card-title">Total Pengguna</h5>
              <p class="card-text">' . $total_users . '</p>
          </div>
      </div>
  </div>
  <div class="col-md-4">
      <div class="card">
          <div class="card-body">
              <h5 class="card-title">Total Karyawan</h5>
              <p class="card-text">' . $total_employees . '</p>
          </div>
      </div>
  </div>
  <div class="col-md-4">
      <div class="card">
          <div class="card-body">
              <h5 class="card-title">Total Departemen</h5>
              <p class="card-text">' . $total_departments . '</p>
          </div>
      </div>
  </div>
</div>

<h2 class="mt-4">Informasi Departemen</h2>
<table class="table table-striped">
  <thead>
    <tr>
      <th>Nama Departemen</th>
      <th>Manajer</th>
      <th>Jumlah Karyawan</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
';

while ($row = $result->fetch_assoc()) {
  $content .= '
    <tr>
      <td>' . $row['department_name'] . '</td>
      <td>' . ($row['manager_name'] ? $row['manager_name'] : 'Belum ditentukan') . '</td>
      <td>' . $row['employee_count'] . '</td>
      <td>
        <a href="view_department.php?id=' . $row['department_id'] . '" class="btn btn-sm btn-primary">Lihat Detail</a>
      </td>
    </tr>
  ';
}

$content .= '
  </tbody>
</table>

<div class="mt-4">
  <h2>Tindakan</h2>
  <ul>
      <li><a href="manage_users.php">Kelola Akun Pengguna</a></li>
      <li><a href="manage_employees.php">Kelola Data Semua Karyawan</a></li>
      <li><a href="view_reports.php">Lihat Laporan HR</a></li>
      <li><a href="manage_policies.php">Kelola Kebijakan HR</a></li>
      <li><a href="manage_departments.php">Kelola Departemen</a></li>
  </ul>
</div>
';

include 'layout.php';
?>

