<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
  header("Location: login.php");
  exit();
}

$department_id = $_GET['id'] ?? null;

if (!$department_id) {
  header("Location: super_admin_dashboard.php");
  exit();
}

$sql = "SELECT * FROM departments WHERE department_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $department_id);
$stmt->execute();
$result = $stmt->get_result();
$department = $result->fetch_assoc();

if (!$department) {
  header("Location: super_admin_dashboard.php");
  exit();
}

$sql = "SELECT e.employee_id, e.first_name, e.last_name, e.position, u.email
        FROM employees e
        JOIN users u ON e.user_id = u.user_id
        WHERE u.department_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $department_id);
$stmt->execute();
$result = $stmt->get_result();

$content = '
<h2>Detail Departemen: ' . $department['department_name'] . '</h2>
<h3>Daftar Karyawan</h3>
<table class="table table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Nama</th>
      <th>Posisi</th>
      <th>Email</th>
    </tr>
  </thead>
  <tbody>
';

while ($row = $result->fetch_assoc()) {
  $content .= '
    <tr>
      <td>' . $row['employee_id'] . '</td>
      <td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>
      <td>' . $row['position'] . '</td>
      <td>' . $row['email'] . '</td>
    </tr>
  ';
}

$content .= '
  </tbody>
</table>
<a href="super_admin_dashboard.php" class="btn btn-primary">Kembali ke Dashboard</a>
';

include 'layout.php';
?>

