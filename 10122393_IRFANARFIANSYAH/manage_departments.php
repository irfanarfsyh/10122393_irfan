<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
  header("Location: login.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['add_department'])) {
    $department_name = $_POST['department_name'];
    $sql = "INSERT INTO departments (department_name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $department_name);
    
    if ($stmt->execute()) {
      $success = "Departemen berhasil ditambahkan.";
    } else {
      $error = "Terjadi kesalahan saat menambahkan departemen. Silakan coba lagi.";
    }
  } elseif (isset($_POST['edit_department'])) {
    $department_id = $_POST['department_id'];
    $department_name = $_POST['department_name'];
    $sql = "UPDATE departments SET department_name = ? WHERE department_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $department_name, $department_id);
    
    if ($stmt->execute()) {
      $success = "Departemen berhasil diperbarui.";
    } else {
      $error = "Terjadi kesalahan saat memperbarui departemen. Silakan coba lagi.";
    }
  } elseif (isset($_POST['delete_department'])) {
    $department_id = $_POST['department_id'];
    $sql = "DELETE FROM departments WHERE department_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $department_id);
    
    if ($stmt->execute()) {
      $success = "Departemen berhasil dihapus.";
    } else {
      $error = "Terjadi kesalahan saat menghapus departemen. Silakan coba lagi.";
    }
  }
}

$sql = "SELECT * FROM departments ORDER BY department_name";
$result = $conn->query($sql);

$content = '
<h2>Kelola Departemen</h2>
' . (isset($success) ? '<div class="alert alert-success">' . $success . '</div>' : '') . '
' . (isset($error) ? '<div class="alert alert-danger">' . $error . '</div>' : '') . '
<table class="table table-striped">
  <thead>
    <tr>
      <th>ID</th>
      <th>Nama Departemen</th>
      <th>Tindakan</th>
    </tr>
  </thead>
  <tbody>
';

while ($row = $result->fetch_assoc()) {
  $content .= '
    <tr>
      <td>' . $row['department_id'] . '</td>
      <td>' . $row['department_name'] . '</td>
      <td>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editDepartmentModal' . $row['department_id'] . '">Edit</button>
        <form method="post" style="display: inline;">
          <input type="hidden" name="department_id" value="' . $row['department_id'] . '">
          <button type="submit" name="delete_department" class="btn btn-sm btn-danger" onclick="return confirm(\'Apakah Anda yakin ingin menghapus departemen ini?\')">Hapus</button>
        </form>
      </td>
    </tr>
    
    <div class="modal fade" id="editDepartmentModal' . $row['department_id'] . '" tabindex="-1" aria-labelledby="editDepartmentModalLabel' . $row['department_id'] . '" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editDepartmentModalLabel' . $row['department_id'] . '">Edit Departemen</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="post">
            <div class="modal-body">
              <input type="hidden" name="department_id" value="' . $row['department_id'] . '">
              <div class="mb-3">
                <label for="department_name' . $row['department_id'] . '" class="form-label">Nama Departemen</label>
                <input type="text" class="form-control" id="department_name' . $row['department_id'] . '" name="department_name" value="' . $row['department_name'] . '" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
              <button type="submit" name="edit_department" class="btn btn-primary">Simpan Perubahan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  ';
}

$content .= '
  </tbody>
</table>

<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
  Tambah Departemen Baru
</button>

<div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-labelledby="addDepartmentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addDepartmentModalLabel">Tambah Departemen Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post">
        <div class="modal-body">
          <div class="mb-3">
            <label for="new_department_name" class="form-label">Nama Departemen</label>
            <input type="text" class="form-control" id="new_department_name" name="department_name" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" name="add_department" class="btn btn-primary">Tambah Departemen</button>
        </div>
      </form>
    </div>
  </div>
</div>
';

include 'layout.php';
?>

