<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] > 3) {
  header("Location: login.php");
  exit();
}

$employee_id = $_GET['id'] ?? null;

if (!$employee_id) {
  header("Location: manage_employees.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $email = $_POST['email'];
  $position = $_POST['position'];
  $department_id = $_POST['department_id'];
  $salary = $_POST['salary'];

  $sql = "UPDATE employees e
          JOIN users u ON e.user_id = u.user_id
          SET e.first_name = ?, e.last_name = ?, u.email = ?, e.position = ?, u.department_id = ?, e.salary = ?
          WHERE e.employee_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssssdi", $first_name, $last_name, $email, $position, $department_id, $salary, $employee_id);

  if ($stmt->execute()) {
      $success = "Data karyawan berhasil diperbarui.";
  } else {
      $error = "Terjadi kesalahan saat memperbarui data karyawan. Silakan coba lagi.";
  }
}

$sql = "SELECT e.*, u.email, u.department_id
      FROM employees e
      JOIN users u ON e.user_id = u.user_id
      WHERE e.employee_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

$sql = "SELECT * FROM departments";
$departments = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

$content = '
<h2>Edit Karyawan</h2>
' . (isset($success) ? '<div class="alert alert-success">' . $success . '</div>' : '') . '
' . (isset($error) ? '<div class="alert alert-danger">' . $error . '</div>' : '') . '
<form method="post">
  <div class="mb-3">
      <label for="first_name" class="form-label">Nama Depan</label>
      <input type="text" class="form-control" id="first_name" name="first_name" value="' . $employee['first_name'] . '" required>
  </div>
  <div class="mb-3">
      <label for="last_name" class="form-label">Nama Belakang</label>
      <input type="text" class="form-control" id="last_name" name="last_name" value="' . $employee['last_name'] . '" required>
  </div>
  <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="email" value="' . $employee['email'] . '" required>
  </div>
  <div class="mb-3">
      <label for="position" class="form-label">Posisi</label>
      <input type="text" class="form-control" id="position" name="position" value="' . $employee['position'] . '" required>
  </div>
  <div class="mb-3">
      <label for="department_id" class="form-label">Departemen</label>
      <select class="form-control" id="department_id" name="department_id" required>
';

foreach ($departments as $department) {
  $selected = $department['department_id'] == $employee['department_id'] ? 'selected' : '';
  $content .= '<option value="' . $department['department_id'] . '" ' . $selected . '>' . $department['department_name'] . '</option>';
}

$content .= '
      </select>
  </div>
  <div class="mb-3">
      <label for="salary" class="form-label">Gaji</label>
      <input type="number" class="form-control" id="salary" name="salary" step="0.01" value="' . $employee['salary'] . '" required>
  </div>
  <button type="submit" class="btn btn-primary">Perbarui Karyawan</button>
</form>
';

include 'layout.php';
?>

