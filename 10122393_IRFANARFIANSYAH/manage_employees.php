<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
  header("Location: login.php");
  exit();
}

$sql = "SELECT e.employee_id, e.first_name, e.last_name, e.position, u.email, d.department_name 
      FROM employees e 
      JOIN users u ON e.user_id = u.user_id 
      LEFT JOIN departments d ON u.department_id = d.department_id";
$result = $conn->query($sql);

$content = '
<h2>Kelola Karyawan</h2>
<table class="table table-striped">
  <thead>
      <tr>
          <th>ID</th>
          <th>Nama</th>
          <th>Posisi</th>
          <th>Email</th>
          <th>Departemen</th>
          <th>Tindakan</th>
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
          <td>' . ($row['department_name'] ?? 'N/A') . '</td>
          <td>
              <a href="edit_employee.php?id=' . $row['employee_id'] . '" class="btn btn-sm btn-primary">Edit</a>
              <a href="delete_employee.php?id=' . $row['employee_id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Apakah Anda yakin ingin menghapus karyawan ini?\')">Hapus</a>
          </td>
      </tr>
  ';
}

$content .= '
  </tbody>
</table>
<a href="add_employee.php" class="btn btn-success">Tambah Karyawan Baru</a>
';

include 'layout.php';
?>

