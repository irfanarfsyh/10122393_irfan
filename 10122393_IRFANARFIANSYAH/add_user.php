<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
  header("Location: login.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $role_id = $_POST['role_id'];
  $department_id = $_POST['department_id'] ?: null;
  $is_employee = isset($_POST['is_employee']) ? true : false;

  // Start transaction
  $conn->begin_transaction();

  try {
    $hashed_password = hash('sha256', $password);

    $sql = "INSERT INTO users (username, email, password, role_id, department_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $username, $email, $hashed_password, $role_id, $department_id);
    $stmt->execute();
    $user_id = $conn->insert_id;

    if ($is_employee) {
      $first_name = $_POST['first_name'];
      $last_name = $_POST['last_name'];
      $position = $_POST['position'];
      $salary = $_POST['salary'];

      $sql_employee = "INSERT INTO employees (user_id, first_name, last_name, position, salary, hire_date) VALUES (?, ?, ?, ?, ?, CURDATE())";
      $stmt_employee = $conn->prepare($sql_employee);
      $stmt_employee->bind_param("isssd", $user_id, $first_name, $last_name, $position, $salary);
      $stmt_employee->execute();
    }

    // Commit transaction
    $conn->commit();
    $success = $is_employee ? "Pengguna dan data karyawan berhasil ditambahkan." : "Pengguna berhasil ditambahkan.";
  } catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    $error = "Terjadi kesalahan saat menambahkan pengguna. Silakan coba lagi. Error: " . $e->getMessage();
  }
}

$sql = "SELECT * FROM roles";
$roles = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

$sql = "SELECT * FROM departments";
$departments = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

$content = '
<h2>Tambah Pengguna Baru</h2>
' . (isset($success) ? '<div class="alert alert-success">' . $success . '</div>' : '') . '
' . (isset($error) ? '<div class="alert alert-danger">' . $error . '</div>' : '') . '
<form method="post">
  <div class="mb-3">
      <label for="username" class="form-label">Username</label>
      <input type="text" class="form-control" id="username" name="username" required>
  </div>
  <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="email" required>
  </div>
  <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" class="form-control" id="password" name="password" required>
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
      <label for="department_id" class="form-label">Departemen</label>
      <select class="form-control" id="department_id" name="department_id">
          <option value="">Tidak Ada</option>
';

foreach ($departments as $department) {
  $content .= '<option value="' . $department['department_id'] . '">' . $department['department_name'] . '</option>';
}

$content .= '
      </select>
  </div>
  <div class="mb-3 form-check">
      <input type="checkbox" class="form-check-input" id="is_employee" name="is_employee">
      <label class="form-check-label" for="is_employee">Tambahkan sebagai Karyawan</label>
  </div>
  <div id="employee_fields" style="display: none;">
    <div class="mb-3">
        <label for="first_name" class="form-label">Nama Depan</label>
        <input type="text" class="form-control" id="first_name" name="first_name">
    </div>
    <div class="mb-3">
        <label for="last_name" class="form-label">Nama Belakang</label>
        <input type="text" class="form-control" id="last_name" name="last_name">
    </div>
    <div class="mb-3">
        <label for="position" class="form-label">Posisi</label>
        <input type="text" class="form-control" id="position" name="position">
    </div>
    <div class="mb-3">
        <label for="salary" class="form-label">Gaji</label>
        <input type="number" class="form-control" id="salary" name="salary" step="0.01">
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Tambah Pengguna</button>
</form>

<script>
document.getElementById("is_employee").addEventListener("change", function() {
    var employeeFields = document.getElementById("employee_fields");
    employeeFields.style.display = this.checked ? "block" : "none";
});
</script>
';

include 'layout.php';
?>

