<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
  header("Location: login.php");
  exit();
}

$user_id = $_GET['id'] ?? null;

if (!$user_id) {
  header("Location: manage_users.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $role_id = $_POST['role_id'];
  $department_id = $_POST['department_id'];
  $new_password = $_POST['new_password'];

  if (!empty($new_password)) {
      $hashed_password = hash('sha256', $new_password);
      $sql = "UPDATE users SET username = ?, email = ?, role_id = ?, department_id = ?, password = ? WHERE user_id = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ssiisi", $username, $email, $role_id, $department_id, $hashed_password, $user_id);
  } else {
      $sql = "UPDATE users SET username = ?, email = ?, role_id = ?, department_id = ? WHERE user_id = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ssiii", $username, $email, $role_id, $department_id, $user_id);
  }

  if ($stmt->execute()) {
      $success = "Pengguna berhasil diperbarui.";
  } else {
      $error = "Terjadi kesalahan saat memperbarui pengguna. Silakan coba lagi.";
  }
}

$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$sql = "SELECT * FROM roles";
$roles = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

$sql = "SELECT * FROM departments";
$departments = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

$content = '
<h2>Edit Pengguna</h2>
' . (isset($success) ? '<div class="alert alert-success">' . $success . '</div>' : '') . '
' . (isset($error) ? '<div class="alert alert-danger">' . $error . '</div>' : '') . '
<form method="post">
  <div class="mb-3">
      <label for="username" class="form-label">Username</label>
      <input type="text" class="form-control" id="username" name="username" value="' . $user['username'] . '" required>
  </div>
  <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="email" value="' . $user['email'] . '" required>
  </div>
  <div class="mb-3">
      <label for="role_id" class="form-label">Peran</label>
      <select class="form-control" id="role_id" name="role_id" required>
';

foreach ($roles as $role) {
  $selected = $role['role_id'] == $user['role_id'] ? 'selected' : '';
  $content .= '<option value="' . $role['role_id'] . '" ' . $selected . '>' . $role['role_name'] . '</option>';
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
  $selected = $department['department_id'] == $user['department_id'] ? 'selected' : '';
  $content .= '<option value="' . $department['department_id'] . '" ' . $selected . '>' . $department['department_name'] . '</option>';
}

$content .= '
      </select>
  </div>
  <div class="mb-3">
      <label for="new_password" class="form-label">Password Baru (kosongkan jika tidak ingin mengubah)</label>
      <input type="password" class="form-control" id="new_password" name="new_password">
  </div>
  <button type="submit" class="btn btn-primary">Perbarui Pengguna</button>
</form>
';

include 'layout.php';
?>

