<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
  header("Location: login.php");
  exit();
}

$department_id = $_SESSION['department_id'];

$sql = "SELECT u.user_id, u.username, u.email, e.first_name, e.last_name, e.position
      FROM users u
      JOIN employees e ON u.user_id = e.user_id
      WHERE u.department_id = ? AND u.role_id = 3";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $department_id);
$stmt->execute();
$result = $stmt->get_result();

$content = '
<h2>Kelola Manajer Departemen</h2>
<table class="table table-striped">
  <thead>
      <tr>
          <th>ID</th>
          <th>Nama</th>
          <th>Username</th>
          <th>Email</th>
          <th>Posisi</th>
          <th>Tindakan</th>
      </tr>
  </thead>
  <tbody>
';

while ($row = $result->fetch_assoc()) {
  $content .= '
      <tr>
          <td>' . $row['user_id'] . '</td>
          <td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>
          <td>' . $row['username'] . '</td>
          <td>' . $row['email'] . '</td>
          <td>' . $row['position'] . '</td>
          <td>
              <a href="edit_manager.php?id=' . $row['user_id'] . '" class="btn btn-sm btn-primary">Edit</a>
              <a href="delete_manager.php?id=' . $row['user_id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Apakah Anda yakin ingin menghapus manajer ini?\')">Hapus</a>
          </td>
      </tr>
  ';
}

$content .= '
  </tbody>
</table>
<a href="add_manager.php" class="btn btn-success">Tambah Manajer Baru</a>
';

include 'layout.php';
?>

