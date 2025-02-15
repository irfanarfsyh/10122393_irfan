<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
  header("Location: login.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $email = $_POST['email'];
  $position = $_POST['position'];
  $department_id = $_POST['department_id'];
  $salary = $_POST['salary'];
  $role_id = $_POST['role_id'];
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Start transaction
  $conn->begin_transaction();

  try {
    // Add user first
    $hashed_password = hash('sha256', $password);
    $sql_user = "INSERT INTO users (username, email, password, role_id, department_id) VALUES (?, ?, ?, ?, ?)";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("sssii", $username, $email, $hashed_password, $role_id, $department_id);
    $stmt_user->execute();
    $user_id = $conn->insert_id;

    // Then add employee
    $sql_employee = "INSERT INTO employees (user_id, first_name, last_name, position, salary, hire_date) VALUES (?, ?, ?, ?, ?, CURDATE())";
    $stmt_employee = $conn->prepare($sql_employee);
    $stmt_employee->bind_param("isssd", $user_id, $first_name, $last_name, $position, $salary);
    $stmt_employee->execute();

    // Commit transaction
    $conn->commit();
    $success = "Karyawan dan akun pengguna berhasil ditambahkan.";
  } catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    $error = "Terjadi kesalahan saat menambahkan karyawan dan akun pengguna. Silakan coba lagi. Error: " . $e->getMessage();
  }
}

$sql = "SELECT * FROM departments";
$departments = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

$sql = "SELECT * FROM roles";
$roles = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

$content = '
<h2>Tambah Karyawan Baru</h2>
' . (isset($success) ? '<div class="alert alert-success">' . $success . '</div>' : '') . '
' . (isset($error) ? '<div class="alert alert-danger">' . $error . '</div>' : '') . '
<form method="post">
  <div class="mb-3">
      <label for="first_name" class="form-label">Nama Depan</label>
      <input type="text" class="form-control" id="first_name" name="first_name" required>
  </div>
  <div class="mb-3">
      <label for="last_name" class="form-label">Nama Belakang</label>
      <input type="text" class="form-control" id="last_name" name="last_name" required>
  </div>
  <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="email" required>
  </div>
  <div class="mb-3">
      <label for="username" class="form-label">Username</label>
      <input type="text" class="form-control" id="username" name="username" required>
  </div>
  <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" id="password" name="password" required>
  </div>
  <div class="mb-3">
      <label for="position" class="form-label">Posisi</label>
      <input type="text" class="form-control" id="position" name="position" required>
  </div>
  <div class="mb-3">
      <label for="department_id" class="form-label">Departemen</label>
      <select class="form-control" id="department_id" name="department_id" required>
';

foreach ($departments as $department) {
  $content .= '<option value="' . $department['department_id'] . '">' . $department['department_name'] . '</option>';
}

$content .= '
      </select>
  </div>
  <div class="mb-3">
      <label for="role_id" class="form-label">Peran</label>
      <select class="form-control" id="role_id" name="role_id" required>
';

foreach ($roles as $role) {
  $content .= '<option value="' . $role['role_id'] . '">' . $role['role_name'] . '</option>';
}

$content .= '
      </select>
  </div>
  <div class="mb-3">
      <label for="salary" class="form-label">Gaji</label>
      <input type="number" class="form-control" id="salary" name="salary" step="0.01" required>
  </div>
  <button type="submit" class="btn btn-primary">Tambah Karyawan</button>
</form>
';

include 'layout.php';
?>

