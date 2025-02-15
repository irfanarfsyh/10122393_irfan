<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
  header("Location: login.php");
  exit();
}

$sql = "SELECT u.user_id, u.username, u.email, r.role_name, d.department_name 
      FROM users u 
      JOIN roles r ON u.role_id = r.role_id 
      LEFT JOIN departments d ON u.department_id = d.department_id";
$result = $conn->query($sql);

$content = '
<h2>Kelola Pengguna</h2>
<table class="table table-striped">
  <thead>
      <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Email</th>
          <th>Peran</th>
          <th>Departemen</th>
          <th>Tindakan</th>
      </tr>
  </thead>
  <tbody>
';

while ($row = $result->fetch_assoc()) {
  $content .= '
      <tr>
          <td>' . $row['user_id'] . '</td>
          <td>' . $row['username'] . '</td>
          <td>' . $row['email'] . '</td>
          <td>' . $row['role_name'] . '</td>
          <td>' . ($row['department_name'] ?? 'N/A') . '</td>
          <td>
              <a href="edit_user.php?id=' . $row['user_id'] . '" class="btn btn-sm btn-primary">Edit</a>
              <a href="delete_user.php?id=' . $row['user_id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Apakah Anda yakin ingin menghapus pengguna ini?\')">Hapus</a>
          </td>
      </tr>
  ';
}

$content .= '
  </tbody>
</table>
<a href="add_user.php" class="btn btn-success">Tambah Pengguna Baru</a>
';

include 'layout.php';
?>

