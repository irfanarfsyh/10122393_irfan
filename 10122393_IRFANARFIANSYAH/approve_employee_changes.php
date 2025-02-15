<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
  header("Location: login.php");
  exit();
}

$department_id = $_SESSION['department_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $log_id = $_POST['log_id'];
  $action = $_POST['action'];

  if ($action == 'approve') {
      $sql = "UPDATE employee_audit_log SET approved = 1 WHERE log_id = ?";
  } else {
      $sql = "UPDATE employee_audit_log SET approved = 0 WHERE log_id = ?";
  }

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $log_id);

  if ($stmt->execute()) {
      $success = "Permintaan perubahan berhasil " . ($action == 'approve' ? 'disetujui' : 'ditolak') . ".";
  } else {
      $error = "Terjadi kesalahan saat memproses permintaan perubahan. Silakan coba lagi.";
  }
}

$sql = "SELECT eal.log_id, e.first_name, e.last_name, eal.field_name, eal.old_value, eal.new_value, eal.changed_at
      FROM employee_audit_log eal
      JOIN employees e ON eal.employee_id = e.employee_id
      JOIN users u ON e.user_id = u.user_id
      WHERE u.department_id = ? AND eal.approved IS NULL";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $department_id);
$stmt->execute();
$result = $stmt->get_result();

$content = '
<h2>Menyetujui Perubahan Data Karyawan</h2>
' . (isset($success) ? '<div class="alert alert-success">' . $success . '</div>' : '') . '
' . (isset($error) ? '<div class="alert alert-danger">' . $error . '</div>' : '') . '
<table class="table table-striped">
  <thead>
      <tr>
          <th>Karyawan</th>
          <th>Bidang</th>
          <th>Nilai Lama</th>
          <th>Nilai Baru</th>
          <th>Waktu Perubahan</th>
          <th>Tindakan</th>
      </tr>
  </thead>
  <tbody>
';

while ($row = $result->fetch_assoc()) {
  $content .= '
      <tr>
          <td>' . $row['first_name'] . ' ' . $row['last_name'] . '</td>
          <td>' . $row['field_name'] . '</td>
          <td>' . $row['old_value'] . '</td>
          <td>' . $row['new_value'] . '</td>
          <td>' . $row['changed_at'] . '</td>
          <td>
              <form method="post" style="display: inline;">
                  <input type="hidden" name="log_id" value="' . $row['log_id'] . '">
                  <button type="submit" name="action" value="approve" class="btn btn-sm btn-success">Setuju</button>
                  <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger">Tolak</button>
              </form>
          </td>
      </tr>
  ';
}

$content .= '
  </tbody>
</table>
';

if ($result->num_rows == 0) {
  $content .= '<p>Tidak ada perubahan data yang menunggu persetujuan saat ini.</p>';
}

include 'layout.php';
?>

